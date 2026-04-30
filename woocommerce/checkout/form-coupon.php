<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle genzee-coupon-row">
	<a href="#" role="button" aria-label="<?php esc_attr_e( 'Enter your coupon code', 'woocommerce' ); ?>" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="woocommerce-info showcoupon genzee-coupon-row__inner">
		<span class="genzee-coupon-row__icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" focusable="false">
				<path d="M20 12l-8 8L4 12V4h8l8 8z"></path>
				<path d="M8.5 8.5h.01"></path>
			</svg>
		</span>
		<span class="genzee-coupon-row__copy">
			<span><?php esc_html_e( 'Have a coupon? Apply it before payment.', 'genzee-jewels' ); ?></span>
		</span>
		<span class="genzee-coupon-row__chevron" aria-hidden="true">
			<svg viewBox="0 0 24 24" focusable="false">
				<path d="M6 9l6 6 6-6"></path>
			</svg>
		</span>
	</a>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none" id="woocommerce-checkout-form-coupon">

	<p class="form-row form-row-first">
		<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'genzee-jewels' ); ?></button>
	</p>

	<div class="clear"></div>
</form>
