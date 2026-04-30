# build-theme.ps1
# Creates a WordPress-ready theme zip.
# Run: powershell -ExecutionPolicy Bypass -File build-theme.ps1

$ThemeName   = "genzee-wordpress-2"
$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$Timestamp   = Get-Date -Format "yyyyMMddHHmmss"
$TempDir     = Join-Path $env:TEMP "genzee-build-$Timestamp"
$ThemeTemp   = Join-Path $TempDir $ThemeName
$OutputZip   = Join-Path $ProjectRoot "$ThemeName.zip"

# Only these files/folders go into the zip -- everything else is local-dev only
$Include = @(
    "style.css",
    "functions.php",
    "index.php",
    "header.php",
    "footer.php",
    "front-page.php",
    "page.php",
    "page-lookbook.php",
    "page-wishlist.php",
    "search.php",
    "inc",
    "assets",
    "template-parts",
    "woocommerce"
)

Write-Host ""
Write-Host "=== Genzee Jewels -- Theme Builder ===" -ForegroundColor Magenta
Write-Host ""

# Remove stale zip
if (Test-Path $OutputZip) {
    Remove-Item $OutputZip -Force
    Write-Host "Removed old zip." -ForegroundColor DarkGray
}

# Create temp theme directory
New-Item -ItemType Directory -Path $ThemeTemp -Force | Out-Null

# Copy each included item
$MissingItems = @()
foreach ($Item in $Include) {
    $Source = Join-Path $ProjectRoot $Item
    $Dest   = Join-Path $ThemeTemp $Item

    if (Test-Path $Source) {
        if ((Get-Item $Source).PSIsContainer) {
            Copy-Item -Path $Source -Destination $Dest -Recurse -Force
        } else {
            Copy-Item -Path $Source -Destination $Dest -Force
        }
        Write-Host "  [+] $Item" -ForegroundColor Green
    } else {
        $MissingItems += $Item
        Write-Host "  [!] $Item - NOT FOUND, skipped" -ForegroundColor Yellow
    }
}

# Verify style.css exists (WordPress requires it at theme root)
$StyleCheck = Join-Path $ThemeTemp "style.css"
if (-not (Test-Path $StyleCheck)) {
    Write-Host ""
    Write-Host "ERROR: style.css missing -- WordPress will reject this zip." -ForegroundColor Red
    Remove-Item $TempDir -Recurse -Force
    exit 1
}

# Zip contents directly: style.css, functions.php, assets/, ...
# Hostinger's file manager can extract Windows backslashes as literal filename
# characters, so create ZIP entries with forward slashes explicitly.
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem
$ZipStream = [System.IO.File]::Open($OutputZip, [System.IO.FileMode]::CreateNew)
$ZipArchive = New-Object System.IO.Compression.ZipArchive($ZipStream, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    Get-ChildItem -Path $ThemeTemp -Recurse -File | ForEach-Object {
        $RelativePath = $_.FullName.Substring($ThemeTemp.Length).TrimStart('\', '/')
        $EntryName = $RelativePath.Replace('\', '/')

        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $ZipArchive,
            $_.FullName,
            $EntryName,
            [System.IO.Compression.CompressionLevel]::Optimal
        ) | Out-Null
    }
} finally {
    $ZipArchive.Dispose()
    $ZipStream.Dispose()
}

# Verify the final archive shape before reporting success.
$ZipEntries = [IO.Compression.ZipFile]::OpenRead($OutputZip)
$RequiredEntries = @(
    "style.css",
    "functions.php",
    "page-lookbook.php",
    "inc/helpers-product.php",
    "assets/css/base.css",
    "assets/css/header.css",
    "assets/css/footer.css",
    "assets/css/components.css",
    "assets/css/front-page.css",
    "assets/css/lookbook.css",
    "assets/js/main.js"
)

$ArchiveNames = $ZipEntries.Entries | ForEach-Object {
    $_.FullName.Replace('\', '/').TrimEnd('/')
}

$MissingArchiveEntries = @()
foreach ($RequiredEntry in $RequiredEntries) {
    if ($ArchiveNames -notcontains $RequiredEntry) {
        $MissingArchiveEntries += $RequiredEntry
    }
}

$ZipEntries.Dispose()

if ($MissingArchiveEntries.Count -gt 0) {
    Write-Host ""
    Write-Host "ERROR: zip is missing required theme files:" -ForegroundColor Red
    $MissingArchiveEntries | ForEach-Object { Write-Host "  - $_" -ForegroundColor Red }
    Remove-Item $TempDir -Recurse -Force
    exit 1
}

# Clean up temp
Remove-Item $TempDir -Recurse -Force

# Report
$ZipItem = Get-Item $OutputZip
$ZipKB   = [math]::Round($ZipItem.Length / 1KB, 1)

Write-Host ""
Write-Host "ZIP CREATED:" -ForegroundColor Cyan
Write-Host "  Path : $OutputZip"
Write-Host "  Size : $ZipKB KB"
Write-Host ""
Write-Host "UPLOAD STEPS:" -ForegroundColor Cyan
Write-Host "  1. WordPress Admin -> Appearance -> Themes"
Write-Host "  2. Click 'Add New' -> 'Upload Theme'"
Write-Host "  3. Choose: $($ZipItem.Name)"
Write-Host "  4. Install -> Activate"
Write-Host ""

if ($MissingItems.Count -gt 0) {
    Write-Host "WARNINGS - these items were not found:" -ForegroundColor Yellow
    $MissingItems | ForEach-Object { Write-Host "  - $_" -ForegroundColor Yellow }
    Write-Host ""
}
