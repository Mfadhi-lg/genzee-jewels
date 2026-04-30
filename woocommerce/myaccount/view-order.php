<?php
/**
 * View order - My Account.
 *
 * @package genzee-jewels
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

$order_id = $order->get_id();
$status   = $order->get_status();
?>

<div class="acct-view-order">

	<div class="acct-section-head acct-section-head--spaced">
		<div>
			<a
				href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
				class="acct-back-link"
				aria-label="<?php esc_attr_e( 'Back to orders', 'genzee-jewels' ); ?>"
			>&larr; <?php esc_html_e( 'All orders', 'genzee-jewels' ); ?></a>
			<h2 class="acct-section-head__title">
				<?php
				printf(
					/* translators: %s: order number. */
					esc_html__( 'Order #%s', 'genzee-jewels' ),
					esc_html( $order->get_order_number() )
				);
				?>
			</h2>
		</div>
		<span class="acct-order-status acct-order-status--<?php echo esc_attr( $status ); ?> acct-order-status--lg">
			<span class="acct-order-status__dot" aria-hidden="true"></span>
			<?php echo esc_html( wc_get_order_status_name( $status ) ); ?>
		</span>
	</div>

	<div class="acct-order-meta">
		<?php
		$date_created = $order->get_date_created();
		if ( $date_created ) :
			?>
			<div class="acct-order-meta__item">
				<span class="acct-order-meta__label"><?php esc_html_e( 'Placed on', 'genzee-jewels' ); ?></span>
				<span class="acct-order-meta__value">
					<time datetime="<?php echo esc_attr( $date_created->format( 'c' ) ); ?>">
						<?php echo esc_html( wc_format_datetime( $date_created ) ); ?>
					</time>
				</span>
			</div>
		<?php endif; ?>

		<?php if ( $order->get_payment_method_title() ) : ?>
			<div class="acct-order-meta__item">
				<span class="acct-order-meta__label"><?php esc_html_e( 'Payment', 'genzee-jewels' ); ?></span>
				<span class="acct-order-meta__value"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
			</div>
		<?php endif; ?>

		<div class="acct-order-meta__item">
			<span class="acct-order-meta__label"><?php esc_html_e( 'Total', 'genzee-jewels' ); ?></span>
			<span class="acct-order-meta__value acct-order-meta__value--total">
				<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
			</span>
		</div>
	</div>

	<?php do_action( 'woocommerce_view_order', $order_id ); ?>

</div>
