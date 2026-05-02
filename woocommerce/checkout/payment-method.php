<?php
/**
 * Output a single payment method
 *
 * @package WooCommerce/Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$genzee_gateway_title = $gateway->get_title();
$genzee_gateway_icon  = $gateway->get_icon();
$genzee_is_razorpay   = false !== stripos( $gateway->id, 'razorpay' ) || false !== stripos( wp_strip_all_tags( $genzee_gateway_title ), 'razorpay' );
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<span class="genzee-payment-method__title">
			<?php echo wp_kses_post( $genzee_gateway_title ); ?>
		</span>
		<?php if ( $genzee_is_razorpay ) : ?>
			<span class="genzee-payment-method__badge"><?php esc_html_e( 'Pay by Razorpay', 'genzee-jewels' ); ?></span>
		<?php elseif ( $genzee_gateway_icon ) : ?>
			<span class="genzee-payment-method__icon"><?php echo $genzee_gateway_icon; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
