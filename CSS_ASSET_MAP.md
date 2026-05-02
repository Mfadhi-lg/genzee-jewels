# CSS Asset Map — Genzee Jewels WordPress Theme

Generated: 2026-05-01 | Cleanup Phase 4

---

## Load Order (functions.php enqueue)

All CSS is enqueued via `wp_enqueue_style()`. Conditional checks use WooCommerce/WordPress template hierarchy functions.

---

## File Inventory

### Always-loaded (every page)

| File | Handle | Purpose | Issues | Recommendation |
|------|--------|---------|--------|----------------|
| `assets/css/base.css` | `genzee-base` | Design tokens, reset, typography, containers, buttons, utilities, animations | Missing z-index token scale | **Keep — add `--z-*` tokens** |
| `assets/css/header.css` | `genzee-header` | Announcement bar, site header, logo, nav, hamburger, mobile drawer, search autocomplete | None significant | **Keep** |
| `assets/css/footer.css` | `genzee-footer` | Footer columns, social links, copyright | Clean | **Keep** |
| `assets/css/components.css` | `genzee-components` | Product card, wishlist toast, live toast | **3 overlapping passes of `.product-card`** — Passes 1 (lines 75–459), 2 (lines 462–729), 3 (lines 930–1135). `!important` overuse in Pass 2. | **Consolidate — merge 3 passes into 1** |
| `assets/css/cart.css` | `genzee-cart` | Cart drawer (z:200-201), cart page, coupon, cross-sells, progress bar | Progress bar vendor-prefix styles duplicated in drawer+page sections; coupon chip styles duplicated | **Keep — extract shared `<progress>` styles** |
| `style.css` | `genzee-style` | WordPress theme header only, 1 line of actual CSS | Not a real stylesheet | **Keep as-is (WP requirement)** |

### Conditionally loaded

| File | Handle | Condition | Purpose | Issues | Recommendation |
|------|--------|-----------|---------|--------|----------------|
| `assets/css/front-page.css` | `genzee-front-page` | `is_front_page()` | Homepage sections: hero, trust strip, categories, impulse, bestsellers, combo-banner, new-drops, spin-reviews, newsletter | **3 overlapping passes** — original (1–533), Phase 10 responsive (534–2426), Phase 13 polish (2427–3117). Phase 13 overrides Phase 10. `.trust-badge` defined here for trust-strip. | **Consolidate — merge 3 passes, reorder by section** |
| `assets/css/search.css` | `genzee-search` | `is_search()` | Search results page, search grid, no-results | Single pass, clean | **Keep** |
| `assets/css/wishlist.css` | `genzee-wishlist` | `is_page('wishlist')` | Wishlist hero, grid, empty state | Single pass, clean, uses CSS vars | **Keep** |
| `assets/css/lookbook.css` | `genzee-lookbook` | `is_page('lookbook')` | Lookbook hero, filter pills, grid | Single pass, clean | **Keep** |
| `assets/css/about.css` | `genzee-about` | `is_page('about')` | About page sections, story, team | Single pass, clean | **Keep** |
| `assets/css/account.css` | `genzee-account` | `is_account_page()` | Account shell, sidebar nav, dashboard, addresses, orders | Single pass, well-organized, `.acct-*` and `.gd-*` namespaces | **Keep** |
| `assets/css/checkout.css` | `genzee-checkout` | `is_checkout()` | Checkout form, billing/shipping, payment, order review, trust badges | Hardcoded hex values instead of CSS vars; `.trust-badge` class name collision with front-page.css (different properties, different pages — no runtime conflict but fragile); `#place_order` uses `border-radius: 12px` not `var(--r-pill)` | **Keep — map hexes to tokens, rename `.trust-badge` → `.checkout-trust-badge`** |
| `assets/css/archive-product.css` | `genzee-archive` | `is_shop()` / `is_product_category()` / `is_product_tag()` | Collection header, filter sidebar/drawer, toolbar, product grid | **2 overlapping passes** — glassmorphism pass (1–981), design-file alignment pass (1328–1871); final `!important` guard block (1756–1871) | **Consolidate — merge into single pass** |
| `assets/css/single-product.css` | `genzee-single-product` | `is_product()` | PDP layout, image gallery, summary, ATC, bundle, reviews, details | Alignment pass at ~line 1852 re-overrides dozens of earlier values | **Consolidate — merge alignment pass into initial definitions** |

---

## Duplicate Responsibility Map

| Duplicate | Files Involved | Severity | Fix |
|-----------|---------------|----------|-----|
| Product card triple-pass | `components.css` lines 75–459, 462–729, 930–1135 | **Critical** | Merge into single definition |
| Front-page triple-pass | `front-page.css` passes 1/2/3 | **Critical** | Merge by section, remove overrides |
| Archive double-pass | `archive-product.css` passes 1/2 + `!important` guard | **High** | Merge into single definition |
| PDP alignment pass | `single-product.css` ~line 1852 block | **High** | Merge into initial definitions |
| `<progress>` vendor styles | `cart.css` drawer section + page section | **Medium** | Extract to shared block |
| `.trust-badge` class collision | `front-page.css` (trust strip), `checkout.css` (payment trust) | **Medium** | Rename checkout version to `.checkout-trust-badge` |
| Hardcoded hex colors | `checkout.css` throughout | **Low** | Map to CSS custom properties |
| Z-index values not tokenized | `header.css`, `cart.css`, `archive-product.css`, `single-product.css`, `components.css` | **Low** | Add `--z-*` tokens to `base.css`, replace raw values |

---

## Z-Index Audit

| Value | Used In | Proposed Token |
|-------|---------|---------------|
| 80 | `archive-product.css` sticky tools | `--z-sticky-tools` |
| 90 | `archive-product.css` filter drawer, `single-product.css` sticky ATC | `--z-filter-drawer` |
| 99 | `header.css` mobile nav dropdown | `--z-nav-dropdown` |
| 100 | `header.css` site header | `--z-header` |
| 120 | `header.css` search autocomplete | `--z-autocomplete` |
| 200 | `cart.css` cart backdrop | `--z-cart-backdrop` |
| 201 | `cart.css` cart drawer | `--z-cart-drawer` |
| 9000 | `components.css` live toast | `--z-toast` |
| 9100 | `components.css` wishlist toast | `--z-toast-high` |

---

## Button Class Audit

| Class | Defined In | Used On |
|-------|-----------|---------|
| `.btn` | `base.css` | Global base |
| `.btn--primary` | `base.css` | Global CTA |
| `.btn--outline` | `base.css` | Global secondary |
| `.btn--ghost` | `base.css` | Global ghost |
| `.product-card__quick-add` | `components.css` | Product cards |
| `.product-card__mobile-cta` | `components.css` | Mobile product cards |
| `.wishlist-hero__link` | `wishlist.css` | Wishlist CTA |
| `.wishlist-hero__clear` | `wishlist.css` | Wishlist clear |
| `.wishlist-empty__button` | `wishlist.css` | Wishlist empty state |
| `#place_order` | `checkout.css` | Checkout submit |
| `.archive-chip` | `archive-product.css` | Filter chips |
| WooCommerce `.button` | `cart.css`, `checkout.css` | WC default buttons |

---

## Notes

- `design_files/` is excluded — React reference only, do not modify
- No CSS file uses `@import` — all loading is via `wp_enqueue_style()`
- No CSS Modules or PostCSS — plain CSS with custom properties
- All CSS variables defined in `base.css :root {}` are available globally since `base.css` loads on every page
