<?php
/**
 * Orders listing - My Account.
 *
 * @package genzee-jewels
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<div class="acct-section-head">
	<h2 class="acct-section-head__title"><?php esc_html_e( 'Orders', 'genzee-jewels' ); ?></h2>
	<p class="acct-section-head__sub"><?php esc_html_e( 'Your complete order history. Tap View for full details.', 'genzee-jewels' ); ?></p>
</div>

<?php if ( $has_orders ) : ?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" aria-label="<?php esc_attr_e( 'Orders', 'genzee-jewels' ); ?>">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>">
						<span class="nobr"><?php echo esc_html( $column_name ); ?></span>
					</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				$status     = $order->get_status();
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $status ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td
							class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>"
							data-title="<?php echo esc_attr( $column_name ); ?>"
						>
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<?php $date_created = $order->get_date_created(); ?>
								<?php if ( $date_created ) : ?>
									<time datetime="<?php echo esc_attr( $date_created->format( 'c' ) ); ?>">
										<?php echo esc_html( wc_format_datetime( $date_created ) ); ?>
									</time>
								<?php endif; ?>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<span class="acct-order-status acct-order-status--<?php echo esc_attr( $status ); ?>">
									<span class="acct-order-status__dot" aria-hidden="true"></span>
									<?php echo esc_html( wc_get_order_status_name( $status ) ); ?>
								</span>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: 1: formatted order total 2: number of items */
										_n(
											'%1$s for %2$s item',
											'%1$s for %2$s items',
											$item_count,
											'woocommerce'
										),
										$order->get_formatted_order_total(),
										$item_count
									)
								);
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );
								if ( ! empty( $actions ) ) :
									foreach ( $actions as $key => $action ) :
										?>
										<a
											href="<?php echo esc_url( $action['url'] ); ?>"
											class="woocommerce-button button <?php echo esc_attr( sanitize_html_class( $key ) ); ?>"
										>
											<?php echo esc_html( $action['name'] ); ?>
										</a>
									<?php
									endforeach;
								endif;
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>

<?php else : ?>

	<div class="acct-empty-state">
		<span class="acct-empty-state__icon" aria-hidden="true">✨</span>
		<p class="acct-empty-state__title"><?php esc_html_e( 'No orders yet', 'genzee-jewels' ); ?></p>
		<p class="acct-empty-state__copy"><?php esc_html_e( 'Time to treat yourself — everything is under ₹499.', 'genzee-jewels' ); ?></p>
		<a
			href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"
			class="button woocommerce-button wc-backward acct-btn acct-btn--primary"
		>
			<?php esc_html_e( 'Browse products', 'genzee-jewels' ); ?>
		</a>
	</div>

<?php endif; ?>
