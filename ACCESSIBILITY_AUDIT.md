# Accessibility Audit

## Issues Checked and Fixed
- **Icon-only Buttons:** Verified that icon-only buttons (`header.php` search, cart, and account icons, `cart-drawer.php` close button, and `product-card.php` wishlist buttons) have descriptive `aria-label` attributes. Wishlist buttons correctly use `data-label-add` and `data-label-remove` for dynamic state updates.
- **Drawers & Modals:** Validated `cart-drawer` and search modal implementation. They use `aria-expanded`, `aria-controls`, `aria-modal`, and `aria-hidden` attributes correctly to indicate state.
- **Forms:** Confirmed the search form correctly maps an `<input>` to a visually hidden `<label class="screen-reader-text">`. The newsletter section was reviewed and found to be a link-based CTA rather than an input form, avoiding label issues.
- **Semantic Structure:** Header, main, and footer landmarks are present and valid (`role="banner"`, `<main>`, `role="contentinfo"`).

## Manual Test Checklist
- [ ] **Keyboard Navigation:** Tab through the homepage, shop, and product pages to ensure all interactive elements (links, buttons, inputs) are reachable and visually distinct when focused.
- [ ] **Focus Management:** Test the mobile menu, search modal, and cart drawer. Ensure focus is trapped within the drawer/modal while open and restored to the trigger button when closed.
- [ ] **Screen Reader Flow:** Use NVDA or VoiceOver to navigate the product gallery, cart drawer, and checkout flow. Ensure dynamic updates (like cart count changes) announce properly via `aria-live="polite"`.
- [ ] **Color Contrast:** Run a final contrast check on high-impact brand colors (hot pink/yellow) against text, particularly inside badges and promotional tiles.

## Deferred Risks
- **WooCommerce Defaults:** Core WooCommerce forms (Checkout, My Account) rely heavily on default plugin markup. We have kept these close to standard to avoid breaking core updates. Any complex a11y refactoring of checkout forms should be managed via dedicated WooCommerce checkout blocks in the future.
