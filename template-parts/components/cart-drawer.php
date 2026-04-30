<?php
/**
 * Cart drawer - real WooCommerce cart items wrapper.
 * Rendered in footer.php before wp_footer().
 * Requires JS (main.js initCartDrawer) to open/close.
 * Falls back to WC cart page when JS is disabled.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$genzee_wc_active  = class_exists( 'WooCommerce' );
$genzee_cart_count = 0;

if ( $genzee_wc_active && function_exists( 'WC' ) && WC()->cart ) {
	$genzee_cart_count = (int) WC()->cart->get_cart_contents_count();
}
?>

<div
	id="cart-drawer-backdrop"
	class="cart-drawer-backdrop"
	aria-hidden="true"
></div>

<aside
	id="cart-drawer"
	class="cart-drawer widget_shopping_cart"
	role="dialog"
	aria-label="<?php esc_attr_e( 'Shopping bag', 'genzee-jewels' ); ?>"
	aria-modal="true"
	aria-hidden="true"
>

	<div class="cart-drawer__header">
		<div class="cart-drawer__title-group">
			<h2 class="cart-drawer__title">
				<?php esc_html_e( 'Your bag', 'genzee-jewels' ); ?>
				<span
					class="cart-drawer__count<?php echo 0 === $genzee_cart_count ? ' is-empty' : ''; ?>"
					id="cart-drawer-count"
					aria-live="polite"
					aria-atomic="true"
				>
					<?php if ( $genzee_cart_count > 0 ) : ?>
					(<?php echo esc_html( $genzee_cart_count ); ?>)
					<?php endif; ?>
				</span>
			</h2>
			<p class="cart-drawer__urgency">
				<?php esc_html_e( 'Current cart contents', 'genzee-jewels' ); ?>
			</p>
		</div>
		<button
			type="button"
			class="cart-drawer__close js-cart-close"
			aria-label="<?php esc_attr_e( 'Close cart', 'genzee-jewels' ); ?>"
		>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" focusable="false">
				<path d="M18 6L6 18M6 6l12 12"/>
			</svg>
		</button>
	</div>

	<?php get_template_part( 'template-parts/components/cart-drawer-inner' ); ?>

</aside>
