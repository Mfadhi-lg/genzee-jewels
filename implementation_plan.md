# Genzee Jewels — WordPress Theme Build (Homepage First)

## 1. Current Project Diagnosis

### What's In The Folder

| Path | Type | Purpose |
|------|------|---------|
| `claude.md` | Rules file | Architecture, brand, code quality, and workflow rules for the AI |
| `design_files/` | Design prototype | **React JSX design mockups** rendered via Babel-in-browser |

### Verdict: This is a **React design prototype**, not a WordPress theme.

There are **zero WordPress files** — no `style.css`, no `functions.php`, no `index.php`, no PHP of any kind. The folder contains:

- **`Genzee Jewels Theme.html`** — Entry HTML that loads React 18 + Babel standalone and renders a Figma-like design canvas with artboards for every page (Homepage, Collection, PDP, Cart Drawer, Search, Lookbook, About).
- **`home.jsx`** — The full homepage layout as a React component (300 lines). This is our **primary reference** for the WordPress homepage build.
- **`shared.jsx`** — All shared components: `SiteHeader`, `SiteFooter`, `ProductCard`, `ProductArt` (SVG jewelry illustrations), `LifestyleArt`, `Stars`, `Price`, `Pill`, `CountdownTimer`, `LivePurchaseToast`, plus mock product/category/review data.
- **`design-canvas.jsx`** — The Figma-like canvas wrapper (pan/zoom/focus). Not needed for WordPress.
- **`app.jsx`** — My Account page directions (Editorial vs Sticker). Not needed for homepage.
- **`data.jsx`** — My Account mock data. Not needed for homepage.
- **`styles.css`** — CSS design system with variables, reset, animation keyframes (97 lines). **Directly reusable.**
- Other JSX files (`collection.jsx`, `pdp.jsx`, `editorial.jsx`, `sticker.jsx`, `other-pages.jsx`, `icons.jsx`, `My Account.html`) — Future page designs, not needed now.

> [!IMPORTANT]
> **The entire WordPress theme must be built from scratch.** The design files serve as the visual spec only — all inline React styles need to be extracted into proper CSS, and the component tree needs to be converted to PHP template parts.

---

## 2. Recommended Folder Structure

```
genzee-wordpress-2/
├── design_files/              ← Keep as-is (reference only)
├── claude.md                  ← Keep as-is (AI rules)
│
├── style.css                  ← Theme metadata + base imports
├── functions.php              ← Enqueue, menus, WooCommerce, theme support
├── index.php                  ← Fallback template
├── front-page.php             ← Homepage (assembles sections)
├── header.php                 ← Site header (announcement bar + nav)
├── footer.php                 ← Site footer
├── screenshot.png             ← Theme preview (later)
│
├── template-parts/
│   ├── sections/              ← Homepage sections (modular)
│   │   ├── hero.php
│   │   ├── trust-strip.php
│   │   ├── categories.php
│   │   ├── impulse-zone.php
│   │   ├── bestsellers.php
│   │   ├── combo-banner.php
│   │   ├── new-drops.php
│   │   ├── spin-reviews.php
│   │   ├── instagram-grid.php
│   │   └── newsletter.php
│   │
│   └── components/            ← Reusable UI pieces
│       ├── product-card.php
│       ├── countdown-timer.php
│       ├── stars.php
│       ├── price.php
│       ├── pill.php
│       └── live-toast.php
│
├── assets/
│   ├── css/
│   │   ├── base.css           ← Variables, reset, typography, animations
│   │   ├── layout.css         ← Grid utilities, container, spacing
│   │   ├── components.css     ← Product card, pills, countdown, stars
│   │   ├── header.css         ← Header + announcement strip
│   │   ├── footer.css         ← Footer styles
│   │   └── homepage.css       ← Section-specific homepage styles
│   │
│   ├── js/
│   │   ├── countdown.js       ← Countdown timer logic
│   │   ├── marquee.js         ← Announcement bar auto-scroll (optional)
│   │   └── live-toast.js      ← Live purchase notification
│   │
│   └── images/                ← SVG icons, placeholder imagery
│       └── (empty for now — product images come from WooCommerce)
│
└── woocommerce/               ← WooCommerce template overrides (future)
    └── (empty for now)
```

---

## 3. Files To Create First (Build Order)

### Phase A — Theme skeleton (must work before anything renders)

| # | File | Why First |
|---|------|-----------|
| 1 | `style.css` | WordPress won't recognize the theme without this |
| 2 | `functions.php` | Enqueue assets, register menus, add theme support, declare WooCommerce support |
| 3 | `index.php` | Required fallback template |
| 4 | `assets/css/base.css` | CSS variables, reset, fonts — everything else depends on this |

### Phase B — Global chrome (header + footer appear on every page)

| # | File | Why |
|---|------|-----|
| 5 | `header.php` | Announcement marquee + logo + nav + icons |
| 6 | `footer.php` | Footer columns + newsletter + copyright |
| 7 | `assets/css/header.css` | Header-specific styling |
| 8 | `assets/css/footer.css` | Footer-specific styling |

### Phase C — Homepage sections (top to bottom)

| # | File | Section |
|---|------|---------|
| 9 | `front-page.php` | Assembler — calls `get_template_part()` for each section |
| 10 | `assets/css/layout.css` | Shared layout / container / grid classes |
| 11 | `assets/css/components.css` | Product card, pill, stars, price, countdown styles |
| 12 | `assets/css/homepage.css` | Section-specific hero, impulse zone, categories, etc. |
| 13 | `template-parts/components/product-card.php` | Needed by 3+ sections |
| 14 | `template-parts/components/*.php` | Stars, Price, Pill, Countdown |
| 15 | `template-parts/sections/hero.php` | Hero split grid |
| 16 | `template-parts/sections/trust-strip.php` | Trust bar |
| 17 | `template-parts/sections/categories.php` | Category tiles |
| 18 | `template-parts/sections/impulse-zone.php` | Under ₹299 dark section |
| 19 | `template-parts/sections/bestsellers.php` | Bestsellers grid |
| 20 | `template-parts/sections/combo-banner.php` | Free shipping nudge |
| 21 | `template-parts/sections/new-drops.php` | Horizontal scroll |
| 22 | `template-parts/sections/spin-reviews.php` | Spin wheel + reviews |
| 23 | `template-parts/sections/instagram-grid.php` | Shop the gram |
| 24 | `template-parts/sections/newsletter.php` | Newsletter CTA |
| 25 | `assets/js/countdown.js` | Deal countdown logic |
| 26 | `assets/js/live-toast.js` | Live purchase toast |

---

## 4. Homepage Section Breakdown

Based on the React `home.jsx` (lines 1–300), the homepage has **10 distinct sections** rendered top to bottom:

```
┌───────────────────────────────────────────────┐
│  ANNOUNCEMENT BAR (marquee — part of header)  │
│  HEADER (logo · nav · search/account/cart)    │
├───────────────────────────────────────────────┤
│  1. HERO SPLIT GRID                           │
│     ├── Big editorial tile (gradient bg +     │
│     │   lifestyle art + "Slay on a budget")   │
│     └── 2×2 mini-grid:                        │
│         ├── Deal of the Day (countdown)       │
│         ├── Earrings tile (butter yellow)     │
│         └── Chains tile (lilac)               │
├───────────────────────────────────────────────┤
│  2. TRUST STRIP                               │
│     Free Shipping · Pink Packaging ·          │
│     7-day Returns · COD Available             │
├───────────────────────────────────────────────┤
│  3. SHOP BY CATEGORY (6 tiles grid)           │
│     Earrings · Necklaces · Rings ·            │
│     Bracelets · Nose Pins · Sets              │
├───────────────────────────────────────────────┤
│  4. IMPULSE ZONE (dark bg, under ₹299)        │
│     "Under ₹299. no guilt." + countdown       │
│     4× product cards                          │
├───────────────────────────────────────────────┤
│  5. BESTSELLERS                               │
│     Filter tabs + 4× product cards            │
├───────────────────────────────────────────────┤
│  6. COMBO BANNER (gradient CTA)               │
│     "Add 2 more = FREE shipping"              │
├───────────────────────────────────────────────┤
│  7. NEW DROPS (horizontal scroll)             │
│     8× product cards in scrollable row        │
├───────────────────────────────────────────────┤
│  8. SPIN WHEEL + REVIEWS (2-col grid)         │
│     ├── Lucky Spin gamification card          │
│     └── 4× review cards (2×2 grid)            │
├───────────────────────────────────────────────┤
│  9. SHOP THE GRAM (6-col image grid)          │
│     Instagram-style lifestyle images          │
├───────────────────────────────────────────────┤
│  10. NEWSLETTER CTA                           │
│      "Get 15% off + early access"             │
├───────────────────────────────────────────────┤
│  FOOTER                                       │
│  LIVE PURCHASE TOAST (fixed, bottom-left)     │
└───────────────────────────────────────────────┘
```

### WooCommerce Data Integration Per Section

| Section | Data Source | Notes |
|---------|-----------|-------|
| Hero | Static / Customizer | Hardcoded copy, editable via theme customizer later |
| Trust Strip | Static | 4 trust badges — static PHP array |
| Categories | `get_terms('product_cat')` | Pull WC product categories + count |
| Impulse Zone | `WC_Product_Query` price ≤ 299 | Real products, fallback to demo if empty |
| Bestsellers | `WC_Product_Query` ordered by sales | Filter by category via tabs |
| Combo Banner | Static | Marketing CTA — static |
| New Drops | `WC_Product_Query` ordered by date | 8 most recent products |
| Spin/Reviews | Static + reviews | Spin = static gamification; reviews from WC product reviews |
| Instagram | Static / plugin | Placeholder grid; integrate Insta feed plugin later |
| Newsletter | Static + form | Email form — integrate Mailchimp/plugin later |

---

## 5. Risks & Unclear Items

> [!WARNING]
> ### Risk: No product images exist yet
> The React prototype uses **SVG-generated jewelry illustrations** (`ProductArt` component) — not real product photos. We need a strategy for the product card thumbnail:
> - **Option A:** Use WooCommerce product featured image (normal approach) — requires products to have images uploaded.
> - **Option B:** Create a colored SVG placeholder fallback when no image exists (similar to the design prototype).
> - **Recommended:** Use Option A with Option B as fallback.

> [!IMPORTANT]
> ### Risk: Inline styles → CSS extraction
> The React prototype has **100% inline styles** — no CSS classes in the JSX. Every style rule must be manually extracted into proper CSS classes. This is the most labor-intensive part of the conversion.

### Other Unclear Items

| Item | Question | Default Assumption |
|------|----------|-------------------|
| **Hero lifestyle image** | Use placeholder SVG or real photo? | SVG placeholder initially, replace with real image via Customizer |
| **Spin-the-wheel** | Is this a real gamification feature or just visual? | Build as visual-only initially; actual gamification is a separate task |
| **Live purchase toast** | Does this need real order data? | Fake data initially; integrate WooCommerce recent orders later |
| **Deal of the Day** | How is the featured deal selected? | Manual selection via Customizer or ACF; static fallback |
| **Category tile colors** | Hardcoded per-category? | Map WC category IDs to color presets; static fallback array |
| **Mobile navigation** | Hamburger menu? What layout? | Yes, hamburger with slide-out drawer — but mobile is a follow-up task |
| **Search functionality** | Live search or standard WP? | Standard WP/WC search initially |

---

## 6. Exact First Implementation Step

### Step 1: Create the theme skeleton (4 files)

Create these 4 files to make WordPress recognize and activate the theme:

#### `style.css`
- Theme name, version, author, description, WooCommerce compatibility tag
- `@import` or reference to `assets/css/base.css`

#### `functions.php`  
- `add_theme_support('woocommerce')`, `title-tag`, `post-thumbnails`, `custom-logo`, `html5`
- `register_nav_menus()` for primary and footer menus
- `wp_enqueue_style()` for Google Fonts (Archivo, Fraunces, Inter, JetBrains Mono) + all CSS files
- `wp_enqueue_script()` for countdown + toast JS
- WooCommerce setup hooks

#### `index.php`
- Minimal fallback: `get_header()` + `the_content()` + `get_footer()`

#### `assets/css/base.css`
- Extracted directly from `design_files/styles.css` — CSS variables, reset, font imports, animation keyframes
- Clean up and extend to full base

> [!NOTE]
> Once these 4 files exist, the theme can be activated in WordPress (it will show a blank page with no errors). Then we proceed to build header → footer → homepage sections one at a time.

---

## Verification Plan

### Automated Tests
- Activate theme in WordPress and confirm no PHP fatal errors
- Load homepage and verify each section renders
- Inspect at 360px, 390px, 768px, 1280px, 1440px widths

### Manual Verification
- Compare each section visually against the React design prototype
- Verify product sections pull from WooCommerce when products exist
- Verify fallback demo content when no WooCommerce products exist
