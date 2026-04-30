<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="woocommerce-checkout-form-wrapper genzee-checkout">
	<div class="container genzee-checkout__container">

		<div class="genzee-checkout__header">
			<p class="genzee-checkout__eyebrow">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M12 3l7 3v5c0 4.5-2.8 8.5-7 10-4.2-1.5-7-5.5-7-10V6l7-3z"></path>
					<path d="M9 12l2 2 4-5"></path>
				</svg>
				<?php esc_html_e( 'Secure checkout', 'genzee-jewels' ); ?>
			</p>
			<h1><?php esc_html_e( 'Checkout', 'woocommerce' ); ?></h1>
			<p><?php esc_html_e( 'Confirm your delivery details and pay securely.', 'genzee-jewels' ); ?></p>
		</div>

		<div class="genzee-checkout__before-form">
			<?php
			// Preserve WooCommerce notices, login prompt, and coupon behavior before the checkout form.
			do_action( 'woocommerce_before_checkout_form', $checkout );
			?>
		</div>

		<?php
		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) :
			?>
			<div class="genzee-checkout__notice-card">
				<?php echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) ); ?>
			</div>
			<?php
		else :
			?>

			<form name="checkout" method="post" class="checkout woocommerce-checkout genzee-checkout__layout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

				<div class="checkout-main-content genzee-checkout__details">
					<?php if ( $checkout->get_checkout_fields() ) : ?>

						<?php
						// Core customer-detail hooks are intentionally kept for validation and third-party fields.
						do_action( 'woocommerce_checkout_before_customer_details' );
						?>

						<div id="customer_details" class="col2-set genzee-checkout__customer-details">
							<div class="col-1 genzee-checkout__billing-column">
								<?php do_action( 'woocommerce_checkout_billing' ); ?>
							</div>
							<div class="col-2 genzee-checkout__shipping-column">
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

					<?php endif; ?>
				</div>

				<aside class="checkout-sidebar genzee-checkout__sidebar">
					<details class="genzee-order-summary-panel" open>
						<summary class="genzee-order-summary-panel__toggle">
							<span><?php esc_html_e( 'Your order', 'woocommerce' ); ?></span>
							<span class="genzee-order-summary-panel__hint" aria-hidden="true">
								<span class="genzee-order-summary-panel__hint-open"><?php esc_html_e( 'Hide items', 'genzee-jewels' ); ?></span>
								<span class="genzee-order-summary-panel__hint-closed"><?php esc_html_e( 'Review items', 'genzee-jewels' ); ?></span>
								<svg viewBox="0 0 24 24" focusable="false">
									<path d="M6 9l6 6 6-6"></path>
								</svg>
							</span>
						</summary>

						<div class="checkout-order-review-wrap genzee-order-summary-panel__body">
							<?php
							// Preserve order-review heading and review hooks for shipping, tax, coupon, and plugin fragments.
							do_action( 'woocommerce_checkout_before_order_review_heading' );
							?>

							<h3 id="order_review_heading" class="screen-reader-text"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

							<div id="order_review" class="woocommerce-checkout-review-order">
								<?php
								$genzee_checkout_payment_priority = has_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment' );

								if ( false !== $genzee_checkout_payment_priority ) {
									remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', $genzee_checkout_payment_priority );
								}

								do_action( 'woocommerce_checkout_order_review' );

								if ( false !== $genzee_checkout_payment_priority ) {
									add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', $genzee_checkout_payment_priority );
								}
								?>
							</div>
						</div>
					</details>

					<div class="genzee-checkout__payment-card">
						<?php
						// Payment is rendered separately so mobile can place it after customer details while preserving gateway hooks and nonce fields.
						if ( false !== $genzee_checkout_payment_priority && function_exists( 'woocommerce_checkout_payment' ) ) {
							woocommerce_checkout_payment();
						}
						?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</aside>

			</form>

		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
