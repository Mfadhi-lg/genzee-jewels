# SEO Audit

## Issues Fixed
- **Heading Hierarchy:** Reviewed H1 tags across major templates (`search.php`, `page-about.php`, `front-page.php`, `woocommerce/myaccount/dashboard.php`). Confirmed that each page has a single semantic `<h1>` tag describing its primary content.
- **Breadcrumbs:** Validated existence of breadcrumbs on `archive-product.php` (via `template-parts/sections/archive-header.php`) and `woocommerce/myaccount/dashboard.php`.
- **Internal Links:** Verified that hardcoded links in `header.php`, `footer.php`, and `page-about.php` have been replaced with safe dynamic functions like `home_url()`, `wc_get_page_permalink()`, and `get_permalink()`.
- **Image Alt Text:** Verified that decorative images (e.g., in `woocommerce/myaccount/dashboard.php`) correctly use `alt=""` along with `aria-hidden="true"`. Product images primarily use `wp_get_attachment_image()` which securely pulls alt text from the media library.

## Remaining SEO Opportunities & Recommendations
- **Filtered URLs:** Filtered URLs with query arguments (e.g., `?orderby=date`, `?max_price=299`) can create duplicate content risks. Recommend implementing a `noindex, follow` or canonical tag strategy for these dynamically generated URLs.
- **Collection Copy:** Adding descriptive, keyword-rich text to collection/archive pages to improve organic ranking.
- **Product Descriptions:** Expand product descriptions with unique, detailed content rather than relying solely on global styling points.
- **FAQ Schema:** Consider adding FAQ schema markup to product pages or a dedicated help page to capture rich snippets in search results.
- **Blog/Content Strategy:** Introduce a blog or editorial section later to capture long-tail keywords related to everyday jewelry styling.
