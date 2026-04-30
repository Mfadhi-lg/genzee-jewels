# Genzee WordPress Theme Cleanup Report

Audit date: 2026-04-30  
Scope: Genzee Jewels custom WordPress/WooCommerce theme source in this repository.  
Mode: Audit and report only. No theme files were edited, moved, renamed, deleted, or refactored.

## Executive Summary

- Current health score: 5.5 / 10.
- Launch readiness: not ready for production launch without cleanup and manual WooCommerce checkout/account QA.
- Overall read: the theme has a strong visual direction and many real WooCommerce integrations, but it also has page-by-page accumulated CSS, heavy WooCommerce template overrides, hardcoded promotion/brand claims, global JavaScript, and several fake or unverified commerce UI elements.
- Required WordPress files: `style.css` and `index.php` are present.
- Important command limitation: `php` and `wp` are not available in this shell, so `php -l` and WP-CLI read-only checks could not be run. Template version freshness must be verified manually against the active WooCommerce plugin.
- `design_files/` was inspected only as visual reference. It should not be edited during cleanup and should not ship inside the production theme package.

Top 10 biggest risks:

1. Heavy WooCommerce overrides in `woocommerce/archive-product.php`, `woocommerce/single-product.php`, `woocommerce/cart/cart.php`, checkout templates, and my-account templates create update risk.
2. `woocommerce/archive-product.php`, `woocommerce/single-product.php`, and `woocommerce/cart/cart.php` have no WooCommerce `@version` header.
3. Cart and PDP show promotions/freebies/free shipping/COD claims that are not visibly tied to WooCommerce settings or real promotion logic.
4. `template-parts/sections/spin-reviews.php` and `template-parts/pages/about-story.php` contain hardcoded review, press, and business volume claims that look unsupported.
5. Global `assets/js/main.js` runs wishlist, cart, archive, PDP, toast, countdown, and dashboard logic on every page.
6. Global CSS is large and patch-stacked, especially `front-page.css`, `archive-product.css`, `cart.css`, `single-product.css`, `account.css`, and `checkout.css`.
7. `woocommerce/myaccount/my-account.php` creates a nested `<main>` inside the global `<main>` opened in `header.php`.
8. Variable product support is only partially safe because the PDP custom swatch layer syncs to WooCommerce variation selects by label text.
9. Checkout payment layout is customized by removing/re-adding payment hooks in `woocommerce/checkout/form-checkout.php`, which can break payment plugins.
10. Header/footer/navigation contain many hardcoded page URLs that may point to missing pages or duplicate real menu configuration.

Top 10 best opportunities:

1. Keep the existing visual direction but standardize buttons, cards, prices, badges, forms, drawers, and empty states.
2. Restore more WooCommerce default behavior on cart/checkout/account while retaining styling.
3. Split `assets/js/main.js` into global, archive, PDP, cart, wishlist, account, and checkout modules loaded only where needed.
4. Consolidate product-card CSS into `components.css` and remove duplicated page overrides.
5. Replace unsupported marketing claims with safer Genzee positioning: trendy fashion jewellery, under Rs 499 where true, giftable packaging, everyday styling, impulse-friendly pricing.
6. Replace fake urgency/reviews/press/stats with real data or remove until verified.
7. Add conditional asset loading and stop loading cart fragments globally unless the cart drawer truly requires it.
8. Normalize WooCommerce notices, coupons, shipping, payment, and totals through core hooks.
9. Add accessibility fixes for drawer/modal focus, icon labels, and keyboard behavior.
10. Add SEO hygiene for filtered URLs, search pages, headings, breadcrumbs, footer links, and product copy.

Checks performed:

- File map: `rg --files`, PowerShell directory listings.
- Risk searches: hardcoded claims, hardcoded URLs, `localStorage`, `wp_enqueue`, `add-to-cart`, WooCommerce hooks, `<main`, escaping helpers, fixed/z-index CSS, live-toast wiring.
- PHP/WP tooling: `php -v` failed because PHP is not installed in the shell; `wp --info` failed because WP-CLI is not installed.

## Priority Fixes

Priority 0 launch blockers:

- Remove or rewrite unsupported claims and fake proof: `template-parts/sections/spin-reviews.php`, `template-parts/pages/about-story.php`, `template-parts/sections/archive-header.php`, `template-parts/sections/product-main.php`, `woocommerce/cart/cart.php`, `template-parts/components/cart-drawer-inner.php`, `template-parts/sections/promo-tile.php`, `header.php`, `footer.php`.
- Audit WooCommerce overrides against the installed WooCommerce version and add/refresh `@version` headers: `woocommerce/archive-product.php`, `woocommerce/single-product.php`, `woocommerce/cart/cart.php`, `woocommerce/checkout/*`, `woocommerce/myaccount/*`.
- Fix nested landmark markup in `woocommerce/myaccount/my-account.php` because `header.php` already opens the global `<main>`.
- Validate cart/checkout totals, shipping, coupon, payment, and order placement with real WooCommerce settings.
- Remove or wire fake coupon/freebie/pincode UI before launch.

Priority 1 should fix soon:

- Split global JS and conditionally enqueue page-specific code from `functions.php`.
- Reduce global CSS and remove duplicate override blocks from `archive-product.css`, `front-page.css`, and `single-product.css`.
- Standardize product cards, buttons, price displays, badges, and form styles.
- Replace hardcoded fallback URLs with menus, WooCommerce page URLs, or verified page links.
- Add focus trapping/escape handling/restored focus for cart drawer, mobile nav, and filter drawer.

Priority 2 polish/optimization:

- Add SEO controls for filtered/search URLs.
- Improve image alt text and product copy patterns.
- Cache or remove expensive count/cross-sell queries.
- Clean dead or abandoned files/functions.
- Add a release packaging rule that excludes `design_files/`, `local/`, `.claude/`, docs, build scripts, and zip artifacts.

## Page-by-Page Audit

### Theme Structure

Root files:

- Required files are present: `style.css`, `index.php`.
- Common templates are present: `header.php`, `footer.php`, `front-page.php`, `page.php`, `search.php`, page templates for about/lookbook/wishlist.
- `functions.php` is large and owns setup, enqueues, WooCommerce support, WooCommerce hook removal, AJAX endpoints, checkout field edits, account helpers, and product query filters. This should be split later into focused includes.
- `style.css` reports Version `1.0.3`; `functions.php:13` defines `GENZEE_THEME_VERSION` as `1.0.5`. This mismatch can confuse cache busting and release tracking.
- `genzee-wordpress-2.zip` is present in the theme root. This should not ship inside the theme package.
- `local/` contains QA screenshots and `sample-products.csv`; useful for development, not production theme distribution.
- `.claude/`, `CLAUDE.md`, `implementation_plan.md`, `README.md`, and `build-theme.ps1` are development artifacts and should be excluded from release builds unless intentionally distributed.
- `design_files/` contains reference React/HTML/CSS design files. Keep as source reference only; do not edit in cleanup and exclude from final theme package.

Files that look abandoned or dead:

- `functions.php:1010` defines `genzee_jewels_woocommerce_hooks()`, but no `add_action()` or direct call was found. It duplicates some hook-removal intent and should be removed or wired in a later cleanup after behavior is confirmed.
- `template-parts/components/live-toast.php` exists and `assets/js/main.js:1152` initializes `#live-toast`, but no active include/localization was found in `footer.php` or `functions.php`. Treat as dead/incomplete until verified.

Files that should remain separate:

- `template-parts/components/product-card.php`, `price.php`, `stars.php`, `cart-mini-product.php`, and WooCommerce section partials are useful reusable boundaries.
- Page-level templates like `page-about.php`, `page-wishlist.php`, and `search.php` should remain separate.

Files that should be merged or reorganized:

- `functions.php` should eventually be split into `inc/enqueue.php`, `inc/wishlist.php`, `inc/cart-ajax.php`, `inc/woocommerce.php`, `inc/checkout.php`, and `inc/account.php`.
- Product-card styling is scattered across `components.css`, `front-page.css`, `archive-product.css`, `single-product.css`, `account.css`, and `wishlist.css`. Consolidate base product-card rules.
- Cart drawer and cart page share concepts but duplicate styles and markup across `cart.css`, `woocommerce/cart/cart.php`, and `template-parts/components/cart-drawer-inner.php`.

### Homepage

Files involved:

- `front-page.php`
- `template-parts/sections/hero.php`
- `categories.php`
- `bestsellers.php`
- `impulse-zone.php`
- `new-drops.php`
- `combo-banner.php`
- `trust-strip.php`
- `spin-reviews.php`
- `instagram-grid.php`
- `newsletter.php`
- `assets/css/front-page.css`
- `assets/js/main.js`

Status: visually complete but content-risky and CSS-heavy.

Issues:

- Homepage sections query products and also have designed fallback/demo states. These need manual verification with real product data.
- `spin-reviews.php` hardcodes reviews and "Verified" labels. This is a launch risk unless those reviews are real.
- `combo-banner.php`, `trust-strip.php`, and other sections make promotion/shipping/return/COD claims that must match store settings.
- `front-page.css` is 3,000+ lines and includes multiple appended cleanup/polish phases. It is fragile and likely contains duplicate or obsolete selectors.

Manual tests:

- Load homepage with WooCommerce active and inactive.
- Load homepage with zero products, few products, and normal catalog size.
- Verify hero/category/new drop/bestseller links go to real URLs.
- Verify mobile layout at 360px, 390px, 768px, and desktop.

### Shop / Collection Page

Files involved:

- `woocommerce/archive-product.php`
- `template-parts/sections/archive-header.php`
- `archive-toolbar.php`
- `archive-filters.php`
- `archive-grid.php`
- `template-parts/components/product-card.php`
- `template-parts/components/promo-tile.php`
- `functions.php`
- `assets/css/archive-product.css`
- `assets/js/main.js`

Status: functional but fragile.

Issues:

- `archive-product.php` is a full custom WooCommerce override with no `@version` header.
- Price and rating filters rely on WooCommerce/native query args, but metal/color handling is custom in `functions.php:1150-1205`.
- `archive-filters.php` fallback category buttons use `filter_category`, but no matching query logic was found. These are fake when real product categories are absent.
- `archive-toolbar.php` runs extra count queries for under-Rs filters and may show misleading counts because those queries do not fully mirror active filters/product visibility.
- `archive-header.php:95` shows "47 sold in last hour", a fake urgency claim unless backed by real data.
- Clear-filter links try to strip `add-to-cart` and `wc-ajax`, which is good, but add-to-cart query pollution should still be manually tested.

Manual tests:

- Filter by min/max price, rating, metal, color, category, and combined filters.
- Clear a single filter and clear all filters.
- Add to cart from filtered URLs and confirm URL/query state is not polluted.
- Test sorting, pagination, grid view toggle, and empty result states.
- Test variable, out-of-stock, sale, and no-image products.

### Product Detail Page

Files involved:

- `woocommerce/single-product.php`
- `template-parts/sections/product-main.php`
- `product-details.php`
- `product-related.php`
- `functions.php`
- `assets/css/single-product.css`
- `assets/js/main.js`

Status: visually rich but fragile around variable products and promotion claims.

Issues:

- `single-product.php` is a full custom WooCommerce override with no `@version` header.
- `functions.php:77-96` removes many default single-product hooks. The template later manually renders product title, price, add-to-cart, gallery, etc.
- `product-main.php` calls WooCommerce add-to-cart, which helps preserve variable product forms, but the custom swatch UI in `main.js:984-1040` syncs by label/value text and is fragile for multiple attributes/localized labels.
- Buy-now link is safe only for simple purchasable products; variable products fall back to an anchor.
- Pincode checker is visual only; no backend/JS handling found.
- Offer chips are visual only and do not apply coupons.
- Countdown/deal language can imply a real expiring sale without evidence.

Manual tests:

- Simple product: add to cart, buy now, quantity, wishlist, gallery.
- Variable product: choose every variation combination, add to cart, stock status, disabled states.
- Out-of-stock product: no purchase path.
- Sale product: price, discount, sale badge.
- Product with no image, no gallery, no category, no related products.
- Reviews and review form if enabled.

### Cart Drawer and Cart Page

Files involved:

- `template-parts/components/cart-drawer.php`
- `template-parts/components/cart-drawer-inner.php`
- `template-parts/components/cart-mini-product.php`
- `woocommerce/cart/cart.php`
- `functions.php`
- `assets/css/cart.css`
- `assets/js/main.js`

Status: functional-looking but high risk because totals/promotions are customized.

Issues:

- `cart.php` has no WooCommerce `@version` header.
- Cart page and drawer hardcode free-shipping thresholds and freebie messaging.
- Cart drawer cross-sell logic runs inside fragments and can be expensive.
- Drawer coupon suggestion buttons are visual only.
- Cart page custom summary may miss edge cases for shipping, taxes, fees, coupons, gateway restrictions, and multi-package shipping.
- Quantity AJAX endpoint uses nonce and cart item key, but quantity parsing should be hardened later.
- Drawer has dialog semantics but no focus trap or restored focus.

Manual tests:

- Add, remove, and update quantity from drawer and cart page.
- Apply valid and invalid coupons from cart page and drawer.
- Confirm shipping/tax/fees match WooCommerce totals.
- Empty cart, single item, multiple items, sale item, variable item.
- Proceed to checkout with logged-in and logged-out sessions.

### Checkout

Files involved:

- `woocommerce/checkout/form-checkout.php`
- `form-billing.php`
- `form-shipping.php`
- `form-coupon.php`
- `review-order.php`
- `payment.php`
- `payment-method.php`
- `terms.php`
- `functions.php`
- `assets/css/checkout.css`

Status: needs manual testing before launch.

Issues:

- Checkout is heavily styled and structurally customized.
- `form-checkout.php:105-125` temporarily removes and re-adds `woocommerce_checkout_payment`. This can break payment plugins that expect default hook structure.
- Several checkout templates have old `@version` headers (`form-billing.php` and `form-shipping.php` show `3.6.0`; `terms.php` shows `3.4.0`; `review-order.php` shows `5.2.0`). Verify against the installed WooCommerce version.
- `functions.php` changes many checkout field classes/placeholders. Good for design, but field validation and address requirements must be verified for target countries/payment methods.
- `payment-method.php:27` echoes gateway icons using the standard WooCommerce pattern, but it is still third-party HTML output and should be reviewed.

Manual tests:

- Guest checkout, logged-in checkout, create-account checkout.
- Billing/shipping required field validation.
- Different payment gateways, including failed payments.
- Coupon apply/remove.
- Terms checkbox.
- Order notes if enabled.
- Mobile checkout and sticky order summary behavior.

### Search

Files involved:

- `search.php`
- `template-parts/sections/search-header.php`
- `search-grid.php`
- `functions.php`
- `header.php`
- `assets/css/search.css`
- `assets/js/main.js`

Status: mostly stable, with SEO/content caveats.

Issues:

- `functions.php` forces default frontend searches to product-only when no explicit non-product `post_type` is provided. This is intentional but should be documented.
- Header/search chips and trending links are static.
- Empty search state includes an operational promise: "DM us on IG, we'll source it".
- Search result pages may be indexed unless SEO plugin settings handle them.

Manual tests:

- Header search submit from desktop and mobile.
- Empty query, no-result query, product-result query.
- Search pagination.
- Search with special characters.
- Search result add-to-cart/wishlist buttons.

### Wishlist

Files involved:

- `page-wishlist.php`
- `template-parts/sections/wishlist-grid.php`
- `template-parts/components/product-card.php`
- `template-parts/sections/product-main.php`
- `header.php`
- `functions.php`
- `assets/css/wishlist.css`
- `assets/js/main.js`

Status: functional but intentionally client-side and non-account-bound.

Issues:

- Wishlist uses browser `localStorage` key `genzee_wishlist_v1`.
- Corrupted JSON is handled by clearing the key, which is good.
- Wishlist endpoint `genzee_wishlist_products` is public and does not require a valid nonce by design. It only returns public product cards and caps IDs, so this is low security risk but medium abuse/performance risk.
- Wishlist does not sync across browsers/devices/accounts.
- Header count depends on client-side sync and should be tested after AJAX-rendered product cards.

Manual tests:

- Save/remove on product card, PDP, wishlist page.
- Corrupt localStorage and reload.
- Clear wishlist.
- Wishlist count in header across pages.
- Logged-in and logged-out behavior.

### Account

Files involved:

- `page.php`
- `woocommerce/myaccount/my-account.php`
- `dashboard.php`
- `navigation.php`
- `form-login.php`
- `orders.php`
- `view-order.php`
- `my-address.php`
- `form-edit-address.php`
- `form-edit-account.php`
- `functions.php`
- `assets/css/account.css`

Status: functional-looking but needs endpoint QA and landmark fix.

Issues:

- `my-account.php` contains `<main class="acct-main" id="acct-main-content">` inside the global `<main>` opened by `header.php`.
- `navigation.php` is intentionally customized and only emits a list. This is reasonable, but endpoint compatibility must be tested.
- `dashboard.php` calculates customer stats and recent orders; this is useful but should be tested on accounts with no orders and many orders.
- Account templates have mixed WooCommerce template versions and should be verified.

Manual tests:

- Login, logout, registration.
- Invalid login/register errors.
- Dashboard no-order and with-order states.
- Orders, view order, downloads if enabled, addresses, account details.
- Password change and email change.

### About and Lookbook

Files involved:

- `page-about.php`
- `template-parts/pages/about-story.php`
- `page-lookbook.php`
- `assets/css/about.css`
- `assets/css/lookbook.css`

Status: visually complete but content-risky.

Issues:

- About page includes placeholder stats, press logos, and business claims.
- Lookbook contains demo/fallback products with large review counts and designed collection copy. It may look like real catalog content even when it is not backed by WooCommerce products.
- Treat both pages as content-staging surfaces until real copy/data is approved.

Manual tests:

- Page templates assigned to correct WordPress pages.
- Links route to real shop/category/product pages.
- No demo/fallback cards appear on production if not intended.

### Header, Footer, Announcement Bar

Files involved:

- `header.php`
- `footer.php`
- `template-parts/components/cart-drawer.php`
- `assets/css/header.css`
- `assets/css/footer.css`
- `assets/js/main.js`

Status: mostly stable but hardcoded and claim-heavy.

Issues:

- Header fallback URLs include `/shop/`, `/bestsellers/`, `/under-299/`, `/sets/`, `/lookbook/`, `/about/`. These must exist or be replaced by menus.
- Header announcement claims are hardcoded.
- Footer contains many hardcoded support/company/legal links that may be empty or 404.
- Header opens global `<main id="main">`; templates must not open another main landmark.
- Mobile nav/search/cart drawer need keyboard/focus QA.

Manual tests:

- Desktop nav, mobile nav, search panel, wishlist link, cart link/drawer.
- Logged-in/logged-out account link.
- Admin-bar view while logged in.
- Footer links and legal/support pages.

## WooCommerce Audit

### Override Inventory

- `woocommerce/archive-product.php`: risky custom archive override; no `@version` header found.
- `woocommerce/single-product.php`: risky custom PDP override; no `@version` header found.
- `woocommerce/cart/cart.php`: risky custom cart override; no `@version` header found.
- `woocommerce/checkout/form-checkout.php`: custom layout; `@version 9.4.0`.
- `woocommerce/checkout/form-coupon.php`: `@version 9.8.0`.
- `woocommerce/checkout/payment.php`: `@version 9.8.0`.
- `woocommerce/checkout/form-billing.php`: `@version 3.6.0`; verify.
- `woocommerce/checkout/form-shipping.php`: `@version 3.6.0`; verify.
- `woocommerce/checkout/payment-method.php`: `@version 3.5.0`; verify.
- `woocommerce/checkout/review-order.php`: `@version 5.2.0`; verify.
- `woocommerce/checkout/terms.php`: `@version 3.4.0`; verify.
- `woocommerce/myaccount/my-account.php`: `@version 3.5.0`; verify.
- `woocommerce/myaccount/navigation.php`: `@version 2.6.0`; verify.
- `woocommerce/myaccount/my-address.php`: `@version 2.6.0`; verify.
- `woocommerce/myaccount/dashboard.php`: `@version 4.4.0`; verify.
- `woocommerce/myaccount/form-login.php`: `@version 9.4.0`.
- `woocommerce/myaccount/orders.php`, `view-order.php`, `form-edit-account.php`, `form-edit-address.php`: `@version 9.7.0`.

### WooCommerce Hook Risks

- `functions.php:77-96` removes core single product hooks. The template manually recreates most UI, but this can break plugin integrations that attach to those hooks.
- `functions.php:1010` defines another hook-removal function that is not called. This is confusing dead code.
- Archive and cart layouts bypass large parts of default WooCommerce output. This increases compatibility risk with filters, plugins, shipping, taxes, and notices.
- Checkout payment hook movement in `form-checkout.php` should be treated as high-risk until tested with all payment gateways.

### Product Card Rendering

- `template-parts/components/product-card.php` is generally well-structured and escaped.
- Variable or non-purchasable products route to the product page instead of AJAX add-to-cart, which is good.
- Product-card wishlist metadata supports client-side localStorage.
- Sale/price display relies on helper functions in `inc/helpers-product.php`; verify sale calculations for variable products.

### Add-To-Cart Behavior

- Product cards use WooCommerce AJAX add-to-cart classes for simple products.
- PDP add-to-cart uses WooCommerce core template output, which helps simple/variable support.
- Bundle add-to-cart only accepts simple purchasable in-stock products and uses nonce validation. This is conservative.
- Buy-now is simple-product-only and should be manually tested.

### Cart/Checkout/Account Risks

- Cart page and drawer display hardcoded promotion thresholds independent of WooCommerce shipping/coupon settings.
- Shipping and tax edge cases need manual QA.
- Checkout field customization should be tested for target billing/shipping countries.
- Account templates are custom enough to require all endpoint tests.
- Empty cart/account states exist, but real plugin notices and errors must be verified.

### WooCommerce Notices

- Archive and single product call `woocommerce_output_all_notices()`.
- Cart page calls notices before the custom layout.
- Checkout/account notices depend on WooCommerce template hooks and should be manually verified.

### Simple vs Variable Product Risks

- Simple products appear supported across product card, PDP, cart, and checkout.
- Variable products need careful manual testing because custom swatches are a JS enhancement layered on top of WooCommerce variation forms.
- Bundle add-to-cart does not support variable products.
- Product related/cross-sell fallback queries should handle no-product states better.

## CSS Audit

CSS files:

- `assets/css/base.css`
- `header.css`
- `footer.css`
- `components.css`
- `front-page.css`
- `archive-product.css`
- `single-product.css`
- `cart.css`
- `checkout.css`
- `account.css`
- `search.css`
- `about.css`
- `lookbook.css`
- `wishlist.css`

Overall status: visually strong but overgrown and fragile.

Key issues:

- Page-specific CSS is conditionally loaded in `functions.php`, but `base.css`, `header.css`, `footer.css`, `components.css`, `style.css`, `cart.css`, and `main.js` are global. `cart.css` is large and is loaded globally for the drawer.
- `base.css:8` imports Google Fonts through CSS `@import`, which is render-blocking and externally dependent.
- `archive-product.css` contains later override blocks around the 1500+ line range that redefine earlier archive toolbar/filter/grid styles. This is duplicate patch layering.
- `front-page.css` includes multiple appended phase blocks, including "RESPONSIVE CLEANUP" and "Phase 13 - Visual polish" sections. This indicates accumulated overrides rather than a clean stylesheet.
- `!important` usage is common in `archive-product.css`, `account.css`, `checkout.css`, `components.css`, `cart.css`, and `single-product.css`.
- Button systems are inconsistent: `.btn`, `.acct-btn`, `.cart-page__checkout`, `.pdp-main__buy-now`, `.genzee-place-order button`, archive chips, coupon buttons, and offer chips.
- Product-card styles are spread across multiple files.
- `base.css` uses broad element styles for buttons, lists, and headings that may affect WooCommerce/plugin markup.
- Checkout/account use more slate/blue utility colors and feel less aligned with the brighter Genzee palette.
- Admin bar handling is incomplete. `checkout.css` includes an admin-bar rule for the checkout sidebar, but sticky header, search panel, mobile nav, cart drawer, archive filter drawer, and sticky PDP add-to-cart need admin-bar QA.
- Fixed/z-index surfaces include header, cart drawer, search panel, archive drawer, and sticky PDP CTA. Their stacking should be normalized.

Duplicate/unused selector risks:

- `archive-product.css`: repeated archive toolbar/filter/grid/promo tile rules.
- `front-page.css`: old section styles plus later phase overrides.
- `single-product.css`: PDP base plus later responsive/override blocks.
- `components.css`: product-card and cart drawer-related component styles overlap with page CSS.
- `account.css` and `checkout.css`: many WooCommerce override selectors with high specificity and `!important`.

Recommended CSS cleanup direction:

- Keep `base.css` for variables/reset/typography only.
- Keep `components.css` for buttons, product card, price, badges, chips, forms, empty states, drawers, notices.
- Keep page CSS for layout only.
- Move cart drawer minimal CSS to a small global component file and load cart page CSS only on cart.
- Remove duplicate later patch blocks only after screenshot/manual regression testing.

## JavaScript Audit

Primary file:

- `assets/js/main.js`

Overall status: works defensively in many places, but it is too broad and globally loaded.

Key observations:

- Single global IIFE initializes mobile nav, search, wishlist, dashboard wishlist preview, cart drawer, cart AJAX reload, cart drawer quantity, cart page quantity, archive filter drawer, archive grid view, PDP gallery/bundle/quantity/swatches, countdowns, live toast, and sticky add-to-cart.
- Global exports/configs: `window.genzeeWishlist`, `window.genzeeCart`, `window.genzeeOpenCartDrawer`, and expected `window.genzeeLiveToast`.
- Wishlist localStorage key: `genzee_wishlist_v1`.
- Corrupted wishlist storage is handled by removing the key.
- Event delegation is used for many dynamic cart/wishlist interactions, which is good.
- jQuery WooCommerce fragment events are namespaced, which is good.

Risks:

- The script runs on pages where most modules are not needed.
- Drawer/modal logic does not include robust focus trapping or restored focus.
- Live toast logic appears incomplete because component rendering/localization is not wired.
- Wishlist preview can re-fetch product cards during sync and should be scoped.
- PDP swatch sync is fragile because it derives attribute behavior from label/value text.
- Countdown timers and offer apply buttons are marketing UI, not commerce logic.
- Quantity AJAX triggers WooCommerce fragment refreshes but also triggers `removed_from_cart` for quantity changes, which may confuse integrations.
- JS strings are not localized.

JS that should be split:

- `global.js`: mobile nav, search, header UI.
- `wishlist.js`: localStorage, product-card/PDP wishlist, wishlist page.
- `cart-drawer.js`: drawer open/close, fragments, drawer quantity.
- `cart-page.js`: cart-page quantity controls.
- `archive.js`: filters drawer, grid view persistence.
- `single-product.js`: gallery, bundle, swatches, sticky CTA, countdown if kept.
- `account.js`: dashboard wishlist preview only.

JS that should be removed or replaced unless wired:

- Live purchase toast unless real data and rendering are implemented.
- Fake offer apply buttons unless they apply real coupons.
- Fake pincode checker unless connected to shipping availability.

## Security Audit

Overall status: no critical direct database or obvious arbitrary output issue found, but several medium/low hardening items exist.

Critical issues:

- None confirmed from static audit.

Medium issues:

- `functions.php` public wishlist endpoint accepts product IDs without requiring a valid nonce. It validates public products and caps results, so exposure is limited, but it can still be abused for repeated product-card rendering.
- `functions.php` cart quantity AJAX should use stricter unslashing/sanitization for quantity and confirm WooCommerce AJAX class availability before calling `WC_AJAX::get_refreshed_fragments()`.
- `woocommerce/checkout/payment-method.php:27` echoes gateway icon HTML from payment gateways. This is common WooCommerce practice but should be accepted knowingly.
- Several templates echo internally buffered HTML with `phpcs:ignore` comments. Most buffers appear built from escaped values, but cleanup should keep these narrow.

Low issues:

- Header/footer hardcoded URLs should use `esc_url()` consistently when generated dynamically. Current static `home_url()` usage is generally escaped.
- JS trusts localStorage as a product ID source, but the server validates IDs before rendering cards.
- Search/filter query args are sanitized, but fallback fake filter args should be removed or implemented.

Positive findings:

- No direct database access found.
- Bundle add-to-cart uses nonce validation.
- Cart quantity AJAX uses nonce validation.
- Checkout/account forms generally retain WooCommerce nonces.
- Most PHP output uses `esc_html`, `esc_attr`, `esc_url`, or `wp_kses_post`.

## Accessibility Audit

Critical issues:

- Nested main landmark: `header.php` opens `<main id="main" class="site-main">`, and `woocommerce/myaccount/my-account.php` opens another `<main>`. This should be changed to a section/div.
- Dialog-like surfaces lack focus management: cart drawer, archive filter drawer, mobile nav/search panel, and sticky/modal-like UI need focus trap, Escape close, and restored focus.

Medium issues:

- Icon-only controls need a full pass for accessible names. Many have labels, but this must be verified after dynamic rendering.
- Offer "Apply" buttons and coupon suggestion chips are buttons with no real action. This is confusing for keyboard and screen-reader users.
- Pincode "Check" button has no visible result behavior.
- Color contrast must be manually checked for pale pastel chips, sale badges, muted text, disabled buttons, and checkout/account states.
- Search panel, cart drawer, and mobile nav need keyboard-only QA.
- Heading order should be checked on homepage, account dashboard, checkout, and PDP because many sections have their own `h1`/`h2` patterns.

Quick wins:

- Fix nested `<main>`.
- Add focus trap/restored focus to drawer/filter/mobile nav.
- Replace fake buttons with links, real actions, or non-button text.
- Add `aria-current` and disabled states consistently for active filters and nav items.
- Add visible focus styles to all custom controls.

Positive findings:

- Skip link exists.
- Many icon buttons use `aria-label`.
- Product images generally use product names as alt text.
- Empty states and live regions exist for wishlist/cart count in some places.

## SEO Audit

Overall status: acceptable base WordPress/WooCommerce SEO surface, but high duplicate/thin URL risk.

Issues:

- Filtered shop URLs can create many duplicate URLs with `min_price`, `max_price`, `rating_filter`, `filter_metal`, `filter_color`, `product_cat`, and tag/category args. No noindex/canonical handling found in the theme.
- Search result pages may be indexable depending on site/plugin settings.
- Footer links may point to missing or thin pages (`shipping`, `returns`, `size-guide`, `contact`, `careers`, `sustainability`, `press`, `wholesale`, `cookies`).
- Header fallback links may point to non-existent collection pages.
- Fake stats, reviews, and press claims can damage trust and may violate platform policies.
- Product schema appears to rely on WooCommerce default structured data, but custom hook removal means this must be manually verified in rendered HTML.
- Breadcrumb behavior is inconsistent: archive uses WooCommerce breadcrumb, PDP has custom breadcrumb, other pages vary.
- Demo/fallback SVG product art is not a substitute for real product imagery.

Quick wins:

- Ensure SEO plugin/noindex rules for search and filtered archive URLs.
- Verify canonical tags for shop, category, filtered, and search pages.
- Replace fake footer links with real pages or hide until pages exist.
- Add short, real collection intro copy on archive/category pages.
- Improve PDP descriptions, material/care fields, and image alt text with real product attributes.
- Keep product names unique and descriptive.

## Performance Audit

Overall status: page weight and query load need cleanup.

Risks:

- Global `main.js` runs all modules on every page.
- Global `cart.css` loads on every page.
- WooCommerce `wc-cart-fragments` is enqueued globally when WooCommerce is active.
- Cart drawer is rendered in `footer.php` on every page and refreshed via fragments.
- Cart drawer cross-sell fallback queries can run during fragment refreshes.
- `archive-toolbar.php` runs count queries for quick filters.
- Homepage product sections run multiple product queries.
- Google Fonts are imported via CSS `@import`.
- Large CSS files with duplicate overrides increase render and maintenance cost.
- `genzee-wordpress-2.zip`, `local/`, and `design_files/` should never be part of production distribution.

Quick wins:

- Conditionalize JS and CSS by template/page.
- Load cart page CSS only on cart; keep only drawer-critical CSS global.
- Consider disabling or limiting cart fragments where not needed.
- Replace CSS `@import` with proper `wp_enqueue_style` or local fonts.
- Cache or remove archive count queries.
- Cache cart drawer recommendations or simplify fallback.
- Exclude dev/design/local artifacts from release builds.

## Content/Claims Audit

High-risk or unsupported claims found:

| Location | Exact Copy | Risk | Recommendation |
|---|---|---:|---|
| `header.php:60` | `⚡ FLAT 50% OFF SITEWIDE &mdash; CODE: <strong>GENZ50</strong>` | High | Keep only if the coupon and sale are active; otherwise remove or replace with seasonal copy. |
| `header.php:61` | `🚚 FREE SHIPPING OVER ₹499` | High | Keep only if WooCommerce shipping settings enforce it. |
| `header.php:62` | `💝 BUY 2 GET 1 FREE ON ALL SETS` | High | Keep only if coupon/promotion logic exists. |
| `header.php:63` | `✨ COD AVAILABLE` | Medium | Keep only if COD gateway is enabled for target shipping zones. |
| `header.php:79` | `Flat 50% off sitewide with code GENZ50. Free shipping over ₹499. Buy 2 get 1 free on all sets. Cash on delivery available.` | High | Keep only if every included promotion/service is active. |
| `footer.php:80` | `Jewelry that slaps. Everything under ₹499. Shipped fast, wrapped in pink, designed to feel like a treat.` | Medium | Keep under-₹499 only if catalog enforces it; rewrite "Shipped fast" unless an SLA exists. |
| `template-parts/sections/hero.php:95` | `Under ₹499` | Medium | Keep only if the linked collection/catalog actually matches. |
| `template-parts/sections/archive-header.php:30-31` | `%s style &middot; all under &#8377;499 &middot; ships in 24hrs` / `%s styles &middot; all under &#8377;499 &middot; ships in 24hrs` | High | Rewrite safely or connect to real catalog/shipping data. |
| `template-parts/sections/archive-header.php:38` | `All styles · all under ₹499 · ships in 24hrs` | High | Rewrite safely or connect to real catalog/shipping data. |
| `template-parts/sections/archive-header.php:95` | `47 sold in last hour` | High | Remove unless backed by real order analytics. |
| `template-parts/pages/about-story.php:29-45` | `2.4M+`, `Pieces shipped`, `180K+`, `Happy besties`, `4.8/5`, `Average rating`, `<₹499`, `On everything` | High | Replace with real data or remove. |
| `template-parts/pages/about-story.php:53-54` | `Quality you can trust`; `We choose pieces that look polished, feel easy to wear, and are made for everyday styling &mdash; not one-time use.` | Medium | Rewrite to avoid quality promise unless QA proof exists. |
| `template-parts/pages/about-story.php:71-78`, `230` | `VOGUE INDIA`, `ELLE`, `YOUR STORY`, `LBB`, `COSMOPOLITAN`, `GRAZIA`, `AS SEEN IN` | High | Remove unless press mentions are real and documented. |
| `template-parts/sections/spin-reviews.php:29` | `Quality for Rs 299? Shook. Wore them to a wedding and got asked where they're from like 5 times.` | High | Replace with real WooCommerce review or remove. |
| `template-parts/sections/spin-reviews.php:38` | `The packaging alone is chef's kiss. Feels premium for what I paid. Repeat customer for life.` | High | Replace with real WooCommerce review or remove. |
| `template-parts/sections/spin-reviews.php:47` | `Super dainty and layers beautifully with my other chains. Not the sturdiest, but for the price it is a 10/10.` | High | Replace with real WooCommerce review or remove. |
| `template-parts/sections/spin-reviews.php:58`, `98` | `REAL REVIEWS`, `Verified` | High | Use only with real verified reviews. |
| `template-parts/sections/trust-strip.php:17-33` | `Free Shipping`, `on orders over ₹499`, `7-day Returns`, `no-questions-asked`, `COD Available`, `pay when it arrives` | Medium | Keep only if policies/gateways match. |
| `template-parts/sections/combo-banner.php:23-53` | `3rd FREE`, `Free shipping over ₹499`, `FREE shipping`, `Mix any styles you love — hit ₹499 and shipping is on us. The more you grab, the better the deal.` | High | Tie to active promotions or remove. |
| `template-parts/sections/promo-tile.php:40-51` | `Buy 2 get 1 FREE`, `Cheapest item is free` | High | Keep only if promotion logic exists. |
| `template-parts/sections/promo-tile.php:87` | `Spend ₹799, get a nose pin free.` | High | Remove unless auto-add logic exists. |
| `template-parts/sections/archive-filters.php:513` | `Spend ₹799, get a nose pin free.` | High | Remove unless auto-add logic exists. |
| `template-parts/sections/product-main.php:461-487` | `Extra 10% off on prepaid orders`, `Code: GENZ10`, `FREE nose pin on orders above ₹799`, `Buy 2 sets, get 3rd FREE`, `Code: COMBO3` | High | Replace with real coupon/apply behavior or remove. |
| `template-parts/sections/product-main.php:506-508` | `Ships within 24 hrs`, `COD available`, `7-day easy returns` | Medium | Keep only if operationally true. |
| `template-parts/sections/impulse-zone.php:145`, `182` | `Trending jewellery under ₹299 — because you deserve it, always.`, `Products under ₹299` | Medium | Keep only if the section query enforces the price range. |
| `woocommerce/myaccount/orders.php:114` | `Time to treat yourself — everything is under ₹499.` | Medium | Keep only if catalog enforces it. |
| `template-parts/sections/newsletter.php:32`, `55` | `Weekly drops. Exclusive codes. No spam, promise 🤞`, `Gimme 15%` | Medium | Keep only if email capture and discount flow are real. |
| `woocommerce/cart/cart.php:170`, `177` | `Add %s more for FREE shipping`, `FREE shipping unlocked!` | High | Keep only if tied to real WooCommerce shipping threshold. |
| `woocommerce/cart/cart.php:335-336` | `Add 1 more item - get a FREE nose pin`, `Valued at ₹149. Auto-added at checkout when eligible.` | High | Remove unless implemented in WooCommerce cart rules. |
| `woocommerce/cart/cart.php:306`, `template-parts/components/cart-drawer-inner.php:452` | `COMBO3`, `GENZ50`, `FIRST15` | High | Show only if these coupons are active and buttons/forms apply them. |
| `template-parts/components/cart-drawer-inner.php:210`, `218` | `Add %s more for FREE shipping`, `FREE shipping unlocked!` | High | Keep only if tied to real WooCommerce shipping threshold. |
| `template-parts/components/cart-drawer-inner.php:404-410` | `Add 1 more item - get a FREE nose pin`; `Valued at ₹149 - auto-added at checkout` | High | Remove unless implemented. |
| `woocommerce/cart/cart.php:423-425`, `cart-drawer-inner.php:546` | `UPI / VISA / MC / COD accepted` | Medium | Keep only if enabled gateways match. |
| `functions.php:1064`, `1073` | `UPI, Cards & NetBanking via Razorpay`, `Fast shipping` | High | Remove unless Razorpay is installed/configured and shipping SLA exists. |
| `woocommerce/myaccount/my-account.php:50` | `7-day easy returns. We've got you covered.` | Medium | Keep only if returns policy is approved. |
| `template-parts/sections/search-grid.php:157` | `DM us on IG, we'll source it` | Medium | Keep only if team can fulfill this. |
| `page-lookbook.php` | Demo products and high review counts | Medium/High | Ensure this never appears as fake live product proof. |

Claim searches that did not find active PHP matches:

- No confirmed active PHP copy for anti-tarnish, waterproof, hypoallergenic, nickel-free, 18k gold plated, lifetime guarantee, tarnish-free, or non-fading.

Safer Genzee copy direction:

- Trendy fashion jewellery.
- Under Rs 499 only where product/catalog data supports it.
- Cute/giftable packaging.
- Everyday styling.
- Impulse-friendly pricing.
- Easy to style.
- Avoid serious quality, durability, medical, material, shipping, return, and guarantee promises unless verified.

## File-by-File Report

| File | Purpose | Status | Issues | Recommendation |
|------|---------|--------|--------|----------------|
| `style.css` | Theme header and global fallback stylesheet | Needs small cleanup | Version `1.0.3` differs from `GENZEE_THEME_VERSION` `1.0.5`; `Tested up to 6.7` may be stale | Sync version and update tested metadata after QA |
| `functions.php` | Theme setup, enqueues, Woo hooks, AJAX, checkout/account logic | Fragile | Too many responsibilities; global assets; dead `genzee_jewels_woocommerce_hooks()`; hardcoded checkout trust badges | Split later by concern; remove dead code after verification |
| `inc/helpers-product.php` | Product helper rendering/pricing/SVG art | Needs manual testing | Sale/price helpers should be checked for variable products | Keep, test with product types |
| `header.php` | Header, announcement bar, nav, global main open | Needs small cleanup | Hardcoded claims/URLs; global `<main>` requires templates not to open nested main | Keep structure; validate/remove claims and URLs |
| `footer.php` | Footer, cart drawer include, global main close | Needs small cleanup | Many hardcoded support/company/legal URLs; cart drawer on every page | Verify links; keep drawer if required |
| `front-page.php` | Homepage template | Needs manual testing | Section fallbacks can mask missing products | Keep, test with real catalog |
| `page.php` | Generic page template and Woo shortcode fallback | Fragile | Injects Woo shortcodes into empty cart/checkout/account pages; block compatibility needs testing | Keep short-term; document behavior |
| `index.php` | Fallback template | Clean | Basic fallback only | Keep |
| `search.php` | Search template | Needs small cleanup | Product-only search behavior depends on functions.php | Keep; verify SEO/noindex |
| `page-about.php` | About page template | Needs manual testing | Content claims in included part | Keep template; clean content |
| `page-lookbook.php` | Lookbook page template | Fragile | Demo products/review counts can appear fake | Replace demo proof or mark clearly |
| `page-wishlist.php` | Wishlist page template | Needs manual testing | Client-side localStorage only | Keep; document limitation |
| `woocommerce/archive-product.php` | Shop/archive override | Risky WooCommerce override | No `@version`; custom loop/filter scaffold | Verify against Woo version; restore hooks where possible |
| `woocommerce/single-product.php` | PDP override | Risky WooCommerce override | No `@version`; custom layout with removed hooks | Verify against Woo version; test product types |
| `woocommerce/cart/cart.php` | Cart page override | Risky WooCommerce override | No `@version`; custom totals/promos/freebie UI | Reconcile with Woo totals/shipping/coupons |
| `woocommerce/checkout/form-checkout.php` | Checkout shell | Risky WooCommerce override | Moves payment hook/layout | Test all gateways; minimize hook changes |
| `woocommerce/checkout/form-billing.php` | Billing fields | Risky WooCommerce override | Old version header; custom markup | Verify against installed Woo |
| `woocommerce/checkout/form-shipping.php` | Shipping fields | Risky WooCommerce override | Old version header | Verify against installed Woo |
| `woocommerce/checkout/form-coupon.php` | Checkout coupon | Needs manual testing | Custom layout | Keep if version current |
| `woocommerce/checkout/review-order.php` | Checkout order summary | Risky WooCommerce override | Old version header; totals edge cases | Verify with coupons/shipping/tax |
| `woocommerce/checkout/payment.php` | Payment methods shell | Needs manual testing | Gateway compatibility | Test all gateways |
| `woocommerce/checkout/payment-method.php` | Gateway radio row | Needs manual testing | Echoes gateway icon HTML | Accept if following Woo pattern; review |
| `woocommerce/checkout/terms.php` | Terms checkbox | Risky WooCommerce override | Old version header | Verify current Woo template |
| `woocommerce/myaccount/my-account.php` | Account shell | Broken | Nested `<main>` landmark | Change to div/section in implementation phase |
| `woocommerce/myaccount/navigation.php` | Account nav | Needs manual testing | Old version header; custom structure | Verify all endpoints |
| `woocommerce/myaccount/dashboard.php` | Account dashboard | Needs manual testing | Stats/recent orders need edge-case tests | Keep with QA |
| `woocommerce/myaccount/form-login.php` | Login/register | Needs manual testing | Custom classes; version check | Test validation and registration |
| `woocommerce/myaccount/orders.php` | Orders list | Needs manual testing | Custom layout | Test empty and populated orders |
| `woocommerce/myaccount/view-order.php` | Order details | Needs manual testing | Custom layout | Test order statuses |
| `woocommerce/myaccount/my-address.php` | Address summary | Risky WooCommerce override | Old version header | Verify current Woo template |
| `woocommerce/myaccount/form-edit-address.php` | Edit address | Needs manual testing | Field classes/layout | Test validation |
| `woocommerce/myaccount/form-edit-account.php` | Edit account | Needs manual testing | Password/email changes | Test validation |
| `template-parts/components/product-card.php` | Reusable product card | Needs small cleanup | Check variable/sale/no-image states | Keep and standardize CSS |
| `template-parts/components/price.php` | Price component | Needs manual testing | Variable/sale display should be verified | Keep |
| `template-parts/components/stars.php` | Rating stars | Clean | Static star rendering | Keep |
| `template-parts/components/promo-tile.php` | Archive promo tile | Fragile | Promo/freebie claims | Remove or connect to real promos |
| `template-parts/components/cart-drawer.php` | Drawer shell | Needs manual testing | Dialog lacks focus trap | Keep; improve accessibility |
| `template-parts/components/cart-drawer-inner.php` | Drawer content/fragments | Fragile | Hardcoded free shipping/freebie/payment claims; cross-sell queries | Reconcile with Woo settings |
| `template-parts/components/cart-mini-product.php` | Mini product tile | Needs manual testing | Demo fallback | Keep if fallback is intentional |
| `template-parts/components/live-toast.php` | Live purchase toast | Duplicate/possibly unused | Not included/localized | Remove or implement later with real data |
| `template-parts/sections/hero.php` | Homepage hero | Needs manual testing | Links/data should be verified | Keep |
| `template-parts/sections/categories.php` | Category grid | Needs manual testing | Hardcoded category fallback URLs/counts | Test with real categories |
| `template-parts/sections/bestsellers.php` | Homepage products | Needs manual testing | Query/fallback behavior | Test with real products |
| `template-parts/sections/impulse-zone.php` | Homepage products | Needs manual testing | Query/fallback behavior | Test with real products |
| `template-parts/sections/new-drops.php` | Homepage products | Needs manual testing | Query/fallback behavior | Test with real products |
| `template-parts/sections/combo-banner.php` | Promo section | Fragile | Buy/free shipping claims | Remove or connect to real promo |
| `template-parts/sections/trust-strip.php` | Trust badges | Fragile | Shipping/return/COD claims | Verify policies |
| `template-parts/sections/spin-reviews.php` | Review section | Broken | Fake/hardcoded reviews and verified labels | Replace with real reviews or remove |
| `template-parts/sections/instagram-grid.php` | Social/gallery section | Needs manual testing | Static/social content | Verify links/assets |
| `template-parts/sections/newsletter.php` | Newsletter signup UI | Fragile | Form may not submit anywhere | Wire to real provider or remove form action |
| `template-parts/sections/archive-header.php` | Archive hero/header | Fragile | Fake urgency and shipping claims | Rewrite safely |
| `template-parts/sections/archive-toolbar.php` | Sort/view/chips | Fragile | Extra count queries; query accuracy | Simplify/cache |
| `template-parts/sections/archive-filters.php` | Filter sidebar | Fragile | Fallback fake category filter arg | Implement or remove fallback |
| `template-parts/sections/archive-grid.php` | Product grid/pagination | Needs manual testing | Promo tiles in product list | Test pagination and accessibility |
| `template-parts/sections/product-main.php` | PDP main purchase area | Fragile | Custom swatches, offers, pincode, countdown | Keep Woo add-to-cart; remove fake UI |
| `template-parts/sections/product-details.php` | PDP details/reviews | Needs manual testing | Review summary and details fallbacks | Test with/without reviews |
| `template-parts/sections/product-related.php` | Related products | Needs manual testing | Fallback query and empty state | Add empty fallback if needed |
| `template-parts/sections/search-header.php` | Search hero/form | Needs small cleanup | Static trending chips | Keep; verify links |
| `template-parts/sections/search-grid.php` | Search results | Needs small cleanup | IG sourcing claim | Rewrite or verify operationally |
| `template-parts/sections/wishlist-grid.php` | Wishlist page shell | Needs manual testing | Client-rendered only | Keep |
| `template-parts/pages/about-story.php` | About page content | Broken | Fake stats/press claims | Replace with approved copy |
| `assets/js/main.js` | All frontend JS | Fragile | Global bundle; page-specific behavior; fake/incomplete modules | Split and conditionally enqueue |
| `assets/css/base.css` | Variables/base typography | Needs small cleanup | Google Fonts `@import`; broad selectors | Enqueue fonts properly; reduce broad rules |
| `assets/css/header.css` | Header/nav/search CSS | Needs manual testing | Admin-bar/mobile overlap risk | Test and normalize z-index |
| `assets/css/footer.css` | Footer CSS | Clean | Link/content issues are in PHP | Keep |
| `assets/css/components.css` | Shared components | Fragile | Overlaps page CSS; `!important` blocks | Consolidate component system |
| `assets/css/front-page.css` | Homepage CSS | Fragile | Very large; phase override blocks | Consolidate after screenshots |
| `assets/css/archive-product.css` | Shop/archive CSS | Fragile | Duplicate selectors; `!important`; mobile drawer | Consolidate and test filters |
| `assets/css/single-product.css` | PDP CSS | Fragile | Large; sticky CTA/drawer z-index; overrides | Consolidate and test variable products |
| `assets/css/cart.css` | Cart drawer/page CSS | Fragile | Loaded globally; large; duplicate cart systems | Split drawer vs cart page |
| `assets/css/checkout.css` | Checkout CSS | Fragile | Heavy Woo overrides; many `!important` | Keep scoped; test checkout |
| `assets/css/account.css` | Account CSS | Fragile | Heavy Woo overrides; many `!important` | Keep scoped; fix landmark |
| `assets/css/search.css` | Search CSS | Needs small cleanup | Mostly page-specific | Keep conditional |
| `assets/css/about.css` | About CSS | Needs small cleanup | Content is bigger issue | Keep conditional |
| `assets/css/lookbook.css` | Lookbook CSS | Needs small cleanup | Demo content risk | Keep conditional |
| `assets/css/wishlist.css` | Wishlist CSS | Needs small cleanup | Client-rendered states | Keep conditional |
| `design_files/*` | Visual design references | Duplicate/possibly unused | Should not ship in production theme | Do not edit; exclude from release |
| `local/*` | QA screenshots/sample CSV | Duplicate/possibly unused | Should not ship | Exclude from release |
| `genzee-wordpress-2.zip` | Built theme artifact | Duplicate/possibly unused | Zip inside theme source/package risk | Exclude from release source |
| `build-theme.ps1` | Build helper | Needs manual testing | Ensure exclusions are correct | Keep dev-only |
| `README.md`, `CLAUDE.md`, `implementation_plan.md` | Docs/dev notes | Duplicate/possibly unused | Not production theme runtime | Exclude from release if needed |

## Manual QA Checklist

Homepage:

- [ ] Homepage loads with WooCommerce active.
- [ ] Homepage loads with no products without showing fake production claims.
- [ ] Hero, category, bestseller, impulse, new drop, combo, trust, review, Instagram, and newsletter sections are responsive.
- [ ] All homepage links resolve.

Shop/filters:

- [ ] Shop archive loads with products.
- [ ] Empty archive state displays correctly.
- [ ] Price filters work.
- [ ] Metal filters work.
- [ ] Color filters work.
- [ ] Rating filters work.
- [ ] Category/tag filters work.
- [ ] Selected/unselected states are clear.
- [ ] Clear single filter and clear all work.
- [ ] Sorting and pagination work with filters.
- [ ] Add-to-cart does not pollute filtered URLs.

Search:

- [ ] Header search opens/closes by mouse and keyboard.
- [ ] Product search returns only products as intended.
- [ ] Empty search state works.
- [ ] Trending chips route correctly.
- [ ] Search pagination works.

Product page:

- [ ] Simple product add-to-cart works.
- [ ] Variable product variations work.
- [ ] Out-of-stock product blocks purchase.
- [ ] Sale price/discount displays correctly.
- [ ] Gallery thumbnails work.
- [ ] Wishlist button syncs.
- [ ] Related products display or hide cleanly.
- [ ] Reviews display correctly.

Wishlist:

- [ ] Header wishlist icon count updates.
- [ ] Product-card save/remove works.
- [ ] PDP save/remove works.
- [ ] Wishlist page loads saved products.
- [ ] Clear all works.
- [ ] Corrupt localStorage does not break the page.

Cart drawer:

- [ ] Open/close works from header and add-to-cart events.
- [ ] Focus is managed by keyboard.
- [ ] Quantity update works.
- [ ] Remove item works.
- [ ] Coupon form works or fake buttons are removed.
- [ ] Cart count syncs.
- [ ] Empty cart state works.

Cart page:

- [ ] Quantity updates work.
- [ ] Remove item works.
- [ ] Coupon apply/remove works.
- [ ] Shipping/tax/fee totals match WooCommerce.
- [ ] Checkout button routes correctly.
- [ ] Empty cart state works.

Checkout:

- [ ] Guest checkout works.
- [ ] Logged-in checkout works.
- [ ] Billing required validation works.
- [ ] Shipping-to-different-address works.
- [ ] Coupons work.
- [ ] Payment methods render and submit.
- [ ] Terms checkbox works.
- [ ] Order placement succeeds.
- [ ] Failed payment/error notices display.

Account:

- [ ] Login works.
- [ ] Register works if enabled.
- [ ] Logout works.
- [ ] Dashboard works for accounts with and without orders.
- [ ] Orders endpoint works.
- [ ] View order works.
- [ ] Addresses edit/save works.
- [ ] Account details edit/save works.

About/lookbook/content:

- [ ] About page has only approved real claims.
- [ ] Lookbook does not show fake catalog proof unless intended.
- [ ] Press/review/stat content is approved.

Responsive/devices:

- [ ] Mobile 360px.
- [ ] Mobile 390px.
- [ ] Tablet 768px.
- [ ] Desktop 1280px.
- [ ] Wide desktop.
- [ ] Logged-in admin with admin bar.
- [ ] Logged-out customer.
- [ ] Keyboard-only navigation.
- [ ] Screen-reader smoke test for drawer/filter/search/account.

## Recommended Cleanup Roadmap

### Priority 0 - Must fix before launch

- Issue: Unsupported/fake marketing claims and proof.
  - Exact files: `header.php`, `footer.php`, `template-parts/sections/spin-reviews.php`, `template-parts/pages/about-story.php`, `template-parts/sections/archive-header.php`, `template-parts/sections/product-main.php`, `template-parts/sections/promo-tile.php`, `template-parts/sections/combo-banner.php`, `template-parts/sections/trust-strip.php`, `woocommerce/cart/cart.php`, `template-parts/components/cart-drawer-inner.php`, `functions.php`.
  - Risk: Customer trust, legal/compliance, ad platform rejection, refund disputes.
  - Recommended fix: Replace with approved safe copy or remove until real data/promo logic exists.
  - Estimated difficulty: medium.

- Issue: Risky WooCommerce override freshness unknown.
  - Exact files: `woocommerce/archive-product.php`, `woocommerce/single-product.php`, `woocommerce/cart/cart.php`, `woocommerce/checkout/*`, `woocommerce/myaccount/*`.
  - Risk: Broken cart/checkout/account after WooCommerce updates.
  - Recommended fix: Compare against active WooCommerce templates, add/update `@version` headers, restore core hooks where practical.
  - Estimated difficulty: high.

- Issue: Nested `<main>` in account.
  - Exact files: `header.php`, `woocommerce/myaccount/my-account.php`.
  - Risk: Invalid landmarks and screen-reader navigation issues.
  - Recommended fix: Convert account inner `<main>` to `<div>` or `<section>` and keep skip target valid.
  - Estimated difficulty: low.

- Issue: Fake/nonfunctional offer, coupon, pincode, freebie UI.
  - Exact files: `template-parts/sections/product-main.php`, `woocommerce/cart/cart.php`, `template-parts/components/cart-drawer-inner.php`.
  - Risk: Users click controls that do nothing or expect benefits not applied.
  - Recommended fix: Remove buttons/copy or wire to real WooCommerce coupon/shipping/free-gift logic.
  - Estimated difficulty: medium.

- Issue: Checkout payment hook customization.
  - Exact files: `woocommerce/checkout/form-checkout.php`, `woocommerce/checkout/payment.php`, `woocommerce/checkout/review-order.php`.
  - Risk: Payment gateway breakage.
  - Recommended fix: Restore closer-to-default hook order where possible and style around core output.
  - Estimated difficulty: high.

### Priority 1 - Should fix soon

- Issue: Global JS bundle.
  - Exact files: `assets/js/main.js`, `functions.php`.
  - Risk: Performance drag and cross-page bugs.
  - Recommended fix: Split modules and enqueue conditionally.
  - Estimated difficulty: medium.

- Issue: CSS duplication and patch layering.
  - Exact files: `assets/css/front-page.css`, `archive-product.css`, `single-product.css`, `components.css`, `cart.css`, `checkout.css`, `account.css`.
  - Risk: Regressions, specificity wars, slow styling changes.
  - Recommended fix: Consolidate component styles and remove duplicate overrides with screenshot QA.
  - Estimated difficulty: high.

- Issue: Hardcoded URLs.
  - Exact files: `header.php`, `footer.php`, `template-parts/sections/*`, `page-lookbook.php`.
  - Risk: 404s and duplicate navigation maintenance.
  - Recommended fix: Use menus, WooCommerce page helpers, verified page IDs, or hide links until pages exist.
  - Estimated difficulty: medium.

- Issue: Drawer/modal accessibility.
  - Exact files: `assets/js/main.js`, `template-parts/components/cart-drawer.php`, `template-parts/sections/archive-filters.php`, `header.php`.
  - Risk: Keyboard users trapped or disoriented.
  - Recommended fix: Add focus trap, Escape close, restored focus, inert/background handling where appropriate.
  - Estimated difficulty: medium.

### Priority 2 - Polish / optimization

- Issue: Filter/search SEO duplication.
  - Exact files: `functions.php`, archive/search templates, SEO plugin settings.
  - Opportunity: Prevent index bloat and duplicate content.
  - Recommended fix: Add noindex/canonical policy through SEO plugin or theme hooks.

- Issue: Archive count/cross-sell queries.
  - Exact files: `template-parts/sections/archive-toolbar.php`, `template-parts/components/cart-drawer-inner.php`.
  - Opportunity: Faster archive and cart fragment refresh.
  - Recommended fix: Cache counts/recommendations or simplify.

- Issue: Font loading.
  - Exact files: `assets/css/base.css`, `functions.php`.
  - Opportunity: Better render performance.
  - Recommended fix: Replace CSS `@import` with `wp_enqueue_style` or local fonts.

- Issue: Theme packaging.
  - Exact files: `build-theme.ps1`, root artifacts.
  - Opportunity: Smaller, cleaner release.
  - Recommended fix: Exclude `design_files/`, `local/`, `.claude/`, docs, zip artifacts, and sample CSV.

### Priority 3 - Future enhancement

- Opportunity: Account-synced wishlist.
  - Why it can wait: Current localStorage wishlist is acceptable for a lightweight launch if clearly understood.

- Opportunity: Real promotion/free-gift engine.
  - Why it can wait: Safer to remove claims first and add promotion logic later.

- Opportunity: Real live purchase/social proof.
  - Why it can wait: Fake social proof should be removed now; real event-driven proof can be added after data is available.

- Opportunity: Blog/content SEO program.
  - Why it can wait: Product/category SEO and launch stability are more urgent.

## Next Implementation Prompts

### CLEANUP IMPLEMENTATION PHASE 1: Critical structure and WooCommerce safety

You are working inside the Genzee Jewels WordPress/WooCommerce theme. This is an implementation phase, but keep edits narrow.

Files to edit:

- `woocommerce/myaccount/my-account.php`
- `woocommerce/archive-product.php`
- `woocommerce/single-product.php`
- `woocommerce/cart/cart.php`
- `woocommerce/checkout/form-checkout.php`
- `woocommerce/checkout/payment.php`
- `woocommerce/checkout/review-order.php`
- `woocommerce/checkout/form-billing.php`
- `woocommerce/checkout/form-shipping.php`
- `woocommerce/checkout/terms.php`
- `woocommerce/myaccount/navigation.php`
- `woocommerce/myaccount/my-address.php`
- `functions.php` only if needed for clearly dead Woo hook cleanup

Do not touch:

- `design_files/`
- CSS redesign work
- JS splitting
- marketing copy except where directly tied to broken Woo behavior
- unrelated templates

Tasks:

1. Fix the nested `<main>` in `woocommerce/myaccount/my-account.php` by changing the inner landmark to a non-main element while preserving classes, skip target behavior, and layout.
2. Add missing WooCommerce template `@version` headers to `archive-product.php`, `single-product.php`, and `cart/cart.php` after comparing with the installed WooCommerce template versions.
3. Review checkout payment hook movement in `form-checkout.php`; restore closer-to-default WooCommerce hook order if possible without redesigning.
4. Remove or document the dead `genzee_jewels_woocommerce_hooks()` function only if it is confirmed unused.
5. Preserve all existing visual classes unless a class change is required for valid markup.

Acceptance checks:

- `php -l` passes on every changed PHP file.
- My Account has only one `<main>` on the rendered page.
- Cart, checkout, and account pages still render.
- WooCommerce notices still display on shop, PDP, cart, checkout, and account.
- No files in `design_files/` are modified.

### CLEANUP IMPLEMENTATION PHASE 2: CSS consolidation and design system cleanup

You are working inside the Genzee Jewels WordPress/WooCommerce theme. Focus only on CSS cleanup and design-system consistency. Do not redesign the brand.

Files to edit:

- `assets/css/base.css`
- `assets/css/components.css`
- `assets/css/front-page.css`
- `assets/css/archive-product.css`
- `assets/css/single-product.css`
- `assets/css/cart.css`
- `assets/css/checkout.css`
- `assets/css/account.css`
- `assets/css/wishlist.css`
- `functions.php` only if enqueue changes are needed for CSS split/loading

Do not touch:

- `design_files/`
- WooCommerce PHP logic
- JavaScript behavior
- content/claims copy
- page structure unless required for CSS class cleanup

Tasks:

1. Centralize button, product-card, price, badge, chip, form, empty-state, drawer, and notice styles into `components.css`.
2. Remove duplicate override blocks from `archive-product.css` and `front-page.css` only after confirming the earlier or later rule is the intended one.
3. Split cart drawer-critical CSS from cart page-only CSS if practical; keep cart drawer styling globally available.
4. Reduce `!important` usage where normal specificity can handle it.
5. Normalize admin-bar offsets and z-index layers for header, search panel, cart drawer, archive filter drawer, and sticky PDP CTA.
6. Do not change the visual direction; preserve screenshots as closely as possible.

Acceptance checks:

- No layout regressions on homepage, shop, PDP, cart drawer, cart page, checkout, account, search, wishlist, about, lookbook.
- Mobile 360px and desktop 1280px screenshots are visually equivalent or cleaner.
- Cart drawer still overlays correctly above page content.
- Admin-bar logged-in view does not overlap sticky UI.
- No files in `design_files/` are modified.

### CLEANUP IMPLEMENTATION PHASE 3: JavaScript cleanup and page-specific loading

You are working inside the Genzee Jewels WordPress/WooCommerce theme. Focus on JavaScript modularization and conditional loading without changing user-facing design.

Files to edit:

- `assets/js/main.js`
- New JS files under `assets/js/` if needed
- `functions.php`
- Related templates only if a missing data attribute or accessible label is required: `header.php`, `template-parts/components/cart-drawer.php`, `template-parts/sections/archive-filters.php`, `template-parts/sections/product-main.php`, `template-parts/sections/wishlist-grid.php`

Do not touch:

- `design_files/`
- CSS cleanup beyond tiny required selectors
- WooCommerce template structure unless required for JS hooks
- content/claim copy except removing fake JS-only behavior if it is not wired

Tasks:

1. Split `assets/js/main.js` into focused modules: global/header, wishlist, cart drawer, cart page, archive, single product, account wishlist preview.
2. Update `functions.php` to enqueue modules only on the pages/templates that need them.
3. Keep `genzeeWishlist` and `genzeeCart` localization only where needed.
4. Remove or disable live-toast initialization unless `template-parts/components/live-toast.php` is rendered and real `genzeeLiveToast` data is localized.
5. Add drawer/filter focus management: Escape close, focus trap, and restored focus.
6. Preserve existing selectors/data attributes unless a narrow template update is required.

Acceptance checks:

- `php -l functions.php` passes.
- No console errors on homepage, shop, PDP, cart, checkout, account, search, wishlist.
- Wishlist localStorage still works with key `genzee_wishlist_v1`.
- Cart drawer opens after AJAX add-to-cart and quantity updates.
- Archive filters and grid view toggle still work.
- PDP gallery, quantity, add-to-cart, and variable product forms still work.
- No files in `design_files/` are modified.

### CLEANUP IMPLEMENTATION PHASE 4: Content/claim cleanup and SEO/accessibility fixes

You are working inside the Genzee Jewels WordPress/WooCommerce theme. Focus on removing unsupported claims, improving SEO hygiene, and fixing accessibility issues. Do not redesign.

Files to edit:

- `header.php`
- `footer.php`
- `template-parts/sections/spin-reviews.php`
- `template-parts/pages/about-story.php`
- `template-parts/sections/archive-header.php`
- `template-parts/sections/product-main.php`
- `template-parts/sections/promo-tile.php`
- `template-parts/sections/combo-banner.php`
- `template-parts/sections/trust-strip.php`
- `template-parts/sections/search-grid.php`
- `woocommerce/cart/cart.php`
- `template-parts/components/cart-drawer-inner.php`
- `functions.php` only for SEO/accessibility hooks if needed

Do not touch:

- `design_files/`
- CSS refactors
- JS module splitting
- WooCommerce layout refactors beyond removing fake controls/copy

Tasks:

1. Remove or rewrite unsupported claims listed in `THEME_CLEANUP_REPORT.md`.
2. Use safer Genzee positioning: trendy fashion jewellery, cute/giftable packaging, everyday styling, impulse-friendly pricing, easy to style.
3. Remove fake reviews, fake press mentions, fake stats, fake urgency, fake freebie auto-add copy, fake coupon apply buttons, and fake pincode check behavior unless real logic exists.
4. Replace hardcoded footer/header links with verified links or hide links that do not exist.
5. Add noindex/canonical handling for search/filtered URLs only if not already handled by the site's SEO plugin; otherwise document plugin dependency.
6. Fix accessible names and button semantics for controls affected by content cleanup.

Acceptance checks:

- `php -l` passes on every changed PHP file.
- No fake review/press/stat/freebie/urgency copy remains.
- Header/footer links route to real pages.
- Shop/search/PDP/cart still render.
- Accessibility smoke test confirms fake buttons are gone or functional.
- No files in `design_files/` are modified.

### CLEANUP IMPLEMENTATION PHASE 5: Final QA and regression testing

You are working inside the Genzee Jewels WordPress/WooCommerce theme. This is final QA and regression cleanup only. Do not start broad refactors.

Files to edit:

- Only files directly required to fix QA defects found during this phase.
- Likely candidates: `functions.php`, changed WooCommerce templates, changed CSS/JS files, changed content templates.

Do not touch:

- `design_files/`
- unrelated files
- large redesigns
- new features

Tasks:

1. Run `php -l` on all changed PHP files.
2. If WP-CLI is available, run read-only checks such as theme status and WooCommerce template status.
3. Manually QA the checklist in `THEME_CLEANUP_REPORT.md`: homepage, shop, filters, search, PDP, wishlist, cart drawer, cart page, checkout, account, about/lookbook, mobile, tablet, desktop, logged-in admin, logged-out customer.
4. Test simple, variable, sale, out-of-stock, no-image, and no-related-products product states.
5. Test coupons, shipping, tax, payment, order placement, account endpoints, and notices.
6. Fix only defects discovered in QA.
7. Update the report or create a short QA notes file with remaining known risks.

Acceptance checks:

- All changed PHP files pass `php -l`.
- No JavaScript console errors in core flows.
- Checkout order can be placed successfully.
- Account login/register/orders/address/account details work.
- Mobile drawer/filter/search interactions are keyboard usable.
- No unsupported claims reappear.
- No files in `design_files/` are modified.
