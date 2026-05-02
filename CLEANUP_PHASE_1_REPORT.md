# CLEANUP PHASE 1: Production Stabilization

## Files Changed

- `assets/js/main.js`
- `footer.php`
- `functions.php`
- `header.php`
- `page-about.php`
- `page-lookbook.php`
- `page-wishlist.php`
- `style.css`
- `template-parts/components/cart-drawer-inner.php`
- `template-parts/components/promo-tile.php`
- `template-parts/pages/about-story.php`
- `template-parts/sections/archive-filters.php`
- `template-parts/sections/archive-header.php`
- `template-parts/sections/combo-banner.php`
- `template-parts/sections/hero.php`
- `template-parts/sections/impulse-zone.php`
- `template-parts/sections/newsletter.php`
- `template-parts/sections/product-main.php`
- `template-parts/sections/search-grid.php`
- `template-parts/sections/spin-reviews.php`
- `template-parts/sections/trust-strip.php`
- `woocommerce/archive-product.php`
- `woocommerce/cart/cart.php`
- `woocommerce/myaccount/my-account.php`
- `woocommerce/myaccount/orders.php`
- `woocommerce/single-product.php`

## Issues Fixed

1. **Invalid Document Structure:** Replaced the nested `<main>` element in `woocommerce/myaccount/my-account.php` with a `<section>` to ensure valid document landmark hierarchy, since `header.php` already opens the global `<main id="main">`.
2. **Fake / Non-functional Commerce UI Removed:** Completely stripped or replaced fake urgency timers, unsupported marketing claims, fake static reviews, fake press logos, and fake free shipping thresholds in various templates (e.g., `spin-reviews.php`, `cart-drawer-inner.php`, `product-main.php`, `about-story.php`, etc.). The copy was updated to neutral brand positioning (e.g., "Trendy fashion jewellery for everyday styling"). 
3. **Theme Version Mismatch:** Synchronized the theme version across `style.css` and `functions.php` to `1.0.5` for consistency.
4. **WooCommerce Overrides Versioning:** Added missing `@version` headers to `woocommerce/archive-product.php`, `woocommerce/single-product.php`, and `woocommerce/cart/cart.php`.
5. **Dead Code:** Removed the unused and dead `genzee_jewels_woocommerce_hooks()` function in `functions.php`.
6. **Generic Page Rendering:** Verified that normal pages properly fallback to `the_content()`.

## Issues Intentionally Not Touched

1. **CSS Refactoring & JS Splitting:** Deferred to subsequent cleanup phases as per instructions to strictly focus on production stabilization.
2. **Checkout Redesign:** Did not revert the hook movement in `woocommerce/checkout/form-checkout.php`. Restoring the default hook order (`woocommerce_checkout_payment` back into `#order_review`) without redesigning would place the payment gateway inside the collapsible mobile `<details>` panel, significantly degrading user experience. Left as-is because WooCommerce AJAX handles independent fragments without breaking core checkout logic.
3. **Filter logic & Product card redesign:** No changes made to archive grid filtering functionality or product card design, focusing strictly on fixing commerce logic/UI fallacies instead.
4. **Design Source Files:** The `design_files/` directory remains untouched.

## Manual QA Checklist

- [ ] Inspect the My Account page on mobile and desktop to ensure no visual regressions resulted from replacing the `<main>` tag with `<section>`. Screen readers should now only announce one main landmark.
- [ ] Thoroughly test the Checkout page with multiple payment gateways to confirm that separating the payment UI into its own `div` outside `#order_review` successfully maintains WooCommerce hook expectations without breaking payment integrations or fragment AJAX.
- [ ] Add items to the cart and view the Cart Drawer / Page to verify that WooCommerce correctly triggers shipping and discount calculations without relying on the fake JavaScript UI thresholds. 
- [ ] Confirm all hardcoded marketing messages (such as "sold in last hour" or fake "as seen in" press badges) are absent across the homepage, shop, PDP, and about pages.
- [ ] Check if verified reviews properly query WooCommerce's core review loop in the `spin-reviews.php` block.
