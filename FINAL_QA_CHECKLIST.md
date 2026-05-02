# Final QA Checklist

## 1. Homepage
- [ ] Header loads correctly (logo, navigation, icons)
- [ ] Announcement bar scrolls smoothly
- [ ] Hero section displays correctly
- [ ] Product sections populate with active products
- [ ] Cart icon count updates correctly
- [ ] Wishlist icon triggers correctly
- [ ] Footer links are functional

## 2. Shop / Collection
- [ ] Product grid loads items
- [ ] Price filter adjusts product visibility
- [ ] Metal filter correctly targets attributes (if active)
- [ ] Color filter correctly targets attributes (if active)
- [ ] Clear filters resets view
- [ ] Pagination works on multi-page catalogs
- [ ] Sort dropdown accurately reorders products
- [ ] Product card links go to correct PDPs
- [ ] Add to cart from grid updates cart and opens drawer

## 3. Search
- [ ] Search for existing item (e.g., "bracelet") yields results
- [ ] Search for broad term (e.g., "gold") yields results
- [ ] Search for nonsense query yields graceful empty state
- [ ] Mobile search modal opens and closes correctly

## 4. Product Page (PDP)
- [ ] Gallery loads and swiping/clicking thumbnails changes main image
- [ ] Price updates if variable product
- [ ] Stock status matches WooCommerce backend
- [ ] Quantity selector increments/decrements
- [ ] Add to cart triggers success and drawer opening
- [ ] Wishlist button toggles saved state
- [ ] Related products section populates
- [ ] Mobile layout is stacked and legible

## 5. Wishlist
- [ ] Save product from card
- [ ] Save product from PDP
- [ ] Header count updates immediately
- [ ] Wishlist page displays saved items
- [ ] Remove item clears it from list
- [ ] Empty state displays gracefully when all items removed
- [ ] Corrupt `localStorage` recovery (clearing cache doesn't break page)

## 6. Cart Drawer
- [ ] Opens via header icon and ATC action
- [ ] Closes via X button and backdrop click
- [ ] Correctly displays added products
- [ ] Remove product updates totals/count
- [ ] Checkout link proceeds to checkout

## 7. Cart Page
- [ ] Displays items correctly
- [ ] Update quantity button works
- [ ] Remove item button works
- [ ] Coupon form (if enabled) applies discount
- [ ] Totals calculation is accurate
- [ ] Checkout link proceeds to checkout

## 8. Checkout
- [ ] Checkout loads with correct cart items
- [ ] Billing required field errors trigger on empty submit
- [ ] Shipping fields logic functions
- [ ] Coupon form (if enabled) applies discount
- [ ] Payment method selection is visible
- [ ] Terms checkbox is visible and required
- [ ] **Do not place real order unless sandbox/test gateway enabled**

## 9. My Account
- [ ] Logged-out state displays login/register forms
- [ ] Registration flow functions (if enabled)
- [ ] Lost password link sends reset email
- [ ] Logged-in dashboard loads stats correctly
- [ ] Orders tab lists past orders
- [ ] Addresses tab allows editing billing/shipping
- [ ] Account details tab allows password/email change
- [ ] Logout ends session gracefully

## 10. About Page
- [ ] Page loads correctly
- [ ] No hardcoded fake claims or unsupported statistics are present
- [ ] CTA links successfully direct to shop/categories

## 11. Responsive Layouts
- [ ] Test layout at 360px
- [ ] Test layout at 390px
- [ ] Test layout at 768px
- [ ] Test layout at 1024px
- [ ] Test layout at 1280px
- [ ] Test layout at 1440px

## 12. Admin-bar Mode
- [ ] Logged-in admin with top admin bar visible
- [ ] Header, drawers, and modals do not overlap badly with WP admin bar

## 13. Browser Console Checks
- [ ] No JS errors on Homepage
- [ ] No JS errors on Shop
- [ ] No JS errors on Product
- [ ] No JS errors on Cart
- [ ] No JS errors on Checkout
- [ ] No JS errors on Account
- [ ] No JS errors on Wishlist
- [ ] No JS errors on Search
