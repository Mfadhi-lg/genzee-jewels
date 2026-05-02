<?php
/**
 * My Account — Dashboard.
 *
 * @package genzee-jewels
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_account_dashboard' );

$gd_user_id = get_current_user_id();
$gd_user    = wp_get_current_user();
$gd_name    = '';

if ( $gd_user instanceof WP_User && $gd_user->exists() ) {
	$gd_name = $gd_user->first_name ?: $gd_user->display_name;
}
$gd_name = $gd_name ?: __( 'there', 'genzee-jewels' );

// ── Customer stats (via WC_Customer for HPOS safety) ─────────────
$gd_order_count = 0;
$gd_total_spent = 0.0;

if ( $gd_user_id > 0 && class_exists( 'WC_Customer' ) ) {
	try {
		$gd_wc_customer = new WC_Customer( $gd_user_id );
		$gd_order_count = (int) $gd_wc_customer->get_order_count();
		$gd_total_spent = (float) $gd_wc_customer->get_total_spent();
	} catch ( \Throwable $gd_ex ) {
		$gd_order_count = 0;
		$gd_total_spent = 0.0;
	}
}

// ── Recent orders ─────────────────────────────────────────────────
$gd_recent_orders = array();
$gd_active_order  = null;

if ( function_exists( 'wc_get_orders' ) ) {
	$gd_recent_orders = wc_get_orders(
		array(
			'customer_id' => $gd_user_id,
			'limit'       => 3,
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);

	$gd_active_list = wc_get_orders(
		array(
			'customer_id' => $gd_user_id,
			'limit'       => 1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'status'      => array( 'wc-processing', 'wc-on-hold' ),
		)
	);
	$gd_active_order = ( is_array( $gd_active_list ) && ! empty( $gd_active_list ) ) ? $gd_active_list[0] : null;
}

// ── Active order data ─────────────────────────────────────────────
$gd_ao_name = __( 'Your order', 'genzee-jewels' );
$gd_ao_img  = '';
$gd_ao_num  = '';
$gd_ao_url  = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : '#';
$gd_ao_step = 1;
$gd_ao_date = '';

if ( $gd_active_order instanceof WC_Abstract_Order ) {
	$gd_ao_num = '#' . $gd_active_order->get_order_number();
	$gd_ao_url = $gd_active_order->get_view_order_url();

	$gd_created = $gd_active_order->get_date_created();
	if ( $gd_created ) {
		$gd_ao_date = wc_format_datetime( $gd_created, 'd M' );
	}

	switch ( $gd_active_order->get_status() ) {
		case 'processing':
			$gd_ao_step = 2;
			break;
		case 'shipped':
			$gd_ao_step = 3;
			break;
		case 'completed':
			$gd_ao_step = 4;
			break;
		default:
			$gd_ao_step = 1;
	}

	foreach ( $gd_active_order->get_items() as $gd_ao_item ) {
		$gd_ao_name = $gd_ao_item->get_name();
		$gd_ao_prod = $gd_ao_item->get_product();
		if ( $gd_ao_prod instanceof WC_Product ) {
			$gd_img_id = $gd_ao_prod->get_image_id();
			if ( $gd_img_id ) {
				$gd_src = wp_get_attachment_image_url( $gd_img_id, 'medium' );
				if ( $gd_src ) {
					$gd_ao_img = $gd_src;
				}
			}
		}
		break;
	}
}

// ── Addresses ────────────────────────────────────────────────────
$gd_billing  = '';
$gd_shipping = '';

if ( $gd_user_id > 0 && class_exists( 'WC_Customer' ) ) {
	try {
		if ( ! isset( $gd_wc_customer ) ) {
			$gd_wc_customer = new WC_Customer( $gd_user_id );
		}
		$gd_billing  = $gd_wc_customer->get_formatted_billing_address();
		$gd_shipping = $gd_wc_customer->get_formatted_shipping_address();
	} catch ( \Throwable $gd_ex ) {}
}

// ── URLs ──────────────────────────────────────────────────────────
$gd_orders_url   = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : '#';
$gd_address_url  = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'edit-address' ) : '#';
$gd_account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$gd_wishlist_url = function_exists( 'genzee_jewels_get_wishlist_page_url' ) ? genzee_jewels_get_wishlist_page_url() : home_url( '/wishlist/' );
$gd_shop_url     = ( function_exists( 'wc_get_page_permalink' ) && wc_get_page_permalink( 'shop' ) ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// ── Status map ───────────────────────────────────────────────────
$gd_status_map = array(
	'pending'    => __( 'Pending', 'genzee-jewels' ),
	'processing' => __( 'Processing', 'genzee-jewels' ),
	'on-hold'    => __( 'On hold', 'genzee-jewels' ),
	'completed'  => __( 'Delivered', 'genzee-jewels' ),
	'cancelled'  => __( 'Cancelled', 'genzee-jewels' ),
	'refunded'   => __( 'Refunded', 'genzee-jewels' ),
	'failed'     => __( 'Failed', 'genzee-jewels' ),
);

// ── Tracking steps ───────────────────────────────────────────────
$gd_steps = array(
	1 => __( 'Ordered', 'genzee-jewels' ),
	2 => __( 'Packed', 'genzee-jewels' ),
	3 => __( 'On way', 'genzee-jewels' ),
	4 => __( 'Delivered', 'genzee-jewels' ),
);
?>
<?php try { ?>

<div class="gd">

	<!-- ── Breadcrumb + title ──────────────────────────── -->
	<div class="gd-head">
		<nav class="gd-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'genzee-jewels' ); ?>">
			<a href="<?php echo esc_url( $gd_account_url ); ?>"><?php esc_html_e( 'My account', 'genzee-jewels' ); ?></a>
			<span aria-hidden="true">/</span>
			<span><?php esc_html_e( 'Dashboard', 'genzee-jewels' ); ?></span>
		</nav>
		<h1 class="gd-head__title"><?php esc_html_e( 'Dashboard', 'genzee-jewels' ); ?></h1>
		<p class="gd-head__sub">
			<?php
			printf(
				/* translators: %s customer first name */
				esc_html__( 'Good to see you, %s. Here\'s what\'s happening.', 'genzee-jewels' ),
				esc_html( $gd_name )
			);
			?>
		</p>
	</div>

	<!-- ── Active order card ───────────────────────────── -->
	<?php if ( $gd_active_order ) : ?>
		<section class="gd-hero-track" aria-label="<?php esc_attr_e( 'Active order', 'genzee-jewels' ); ?>">
			<div class="gd-hero-track__left">
				<p class="gd-hero-track__eyebrow">
					<?php
					if ( $gd_ao_num || $gd_ao_date ) {
						echo esc_html( trim( $gd_ao_num . ( $gd_ao_date ? '  ·  ' . $gd_ao_date : '' ) ) );
					} else {
						esc_html_e( 'Active order', 'genzee-jewels' );
					}
					?>
				</p>
				<h2 class="gd-hero-track__title">
					<?php
					printf(
						/* translators: %s product name */
						esc_html__( 'Your %s is on its way', 'genzee-jewels' ),
						esc_html( $gd_ao_name )
					);
					?>
				</h2>

				<!-- Tracking steps -->
				<div class="gd-steps" aria-label="<?php esc_attr_e( 'Order status', 'genzee-jewels' ); ?>">
					<?php foreach ( $gd_steps as $gd_sn => $gd_slabel ) :
						$gd_s_state = '';
						if ( $gd_ao_step > $gd_sn ) {
							$gd_s_state = 'done';
						} elseif ( $gd_ao_step === $gd_sn ) {
							$gd_s_state = 'current';
						}
					?>
						<div class="gd-steps__step gd-steps__step--<?php echo esc_attr( $gd_s_state ?: 'pending' ); ?>">
							<div class="gd-steps__bar" aria-hidden="true"></div>
							<span class="gd-steps__lbl"><?php echo esc_html( $gd_slabel ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="gd-hero-track__actions">
					<a class="gd-btn gd-btn--primary" href="<?php echo esc_url( $gd_ao_url ); ?>">
						<?php esc_html_e( 'Track order', 'genzee-jewels' ); ?>
					</a>
					<a class="gd-btn gd-btn--ghost" href="<?php echo esc_url( $gd_orders_url ); ?>">
						<?php esc_html_e( 'All orders', 'genzee-jewels' ); ?>
					</a>
				</div>
			</div>

			<div class="gd-hero-track__right" aria-hidden="true">
				<span class="gd-hero-track__stamp"><?php esc_html_e( 'In transit', 'genzee-jewels' ); ?></span>
				<div class="gd-hero-track__frame">
					<?php if ( $gd_ao_img ) : ?>
						<img src="<?php echo esc_url( $gd_ao_img ); ?>" alt="" loading="lazy">
					<?php else : ?>
						<span><?php esc_html_e( 'product image', 'genzee-jewels' ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php else : ?>
		<div class="gd-no-order">
			<div>
				<p class="gd-no-order__eyebrow"><?php esc_html_e( 'No active orders', 'genzee-jewels' ); ?></p>
				<p class="gd-no-order__copy"><?php esc_html_e( 'Ready to add some sparkle?', 'genzee-jewels' ); ?></p>
			</div>
			<a class="gd-btn gd-btn--primary" href="<?php echo esc_url( $gd_shop_url ); ?>">
				<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<!-- ── Stats ───────────────────────────────────────── -->
	<div class="gd-stats">
		<div class="gd-stat">
			<span class="gd-stat__lbl"><?php esc_html_e( 'Orders', 'genzee-jewels' ); ?></span>
			<span class="gd-stat__val"><?php echo esc_html( $gd_order_count ); ?></span>
			<span class="gd-stat__delta"><?php esc_html_e( 'Lifetime total', 'genzee-jewels' ); ?></span>
		</div>
		<div class="gd-stat">
			<span class="gd-stat__lbl"><?php esc_html_e( 'Total spent', 'genzee-jewels' ); ?></span>
			<span class="gd-stat__val gd-stat__val--price"><?php echo wp_kses_post( wc_price( $gd_total_spent ) ); ?></span>
			<span class="gd-stat__delta"><?php esc_html_e( 'All time', 'genzee-jewels' ); ?></span>
		</div>
		<div class="gd-stat">
			<span class="gd-stat__lbl"><?php esc_html_e( 'Wishlist', 'genzee-jewels' ); ?></span>
			<span class="gd-stat__val"><span data-wishlist-count aria-live="polite">0</span></span>
			<span class="gd-stat__delta"><?php esc_html_e( 'Saved pieces', 'genzee-jewels' ); ?></span>
		</div>
	</div>

	<!-- ── Recent orders ───────────────────────────────── -->
	<section class="gd-orders-section" aria-labelledby="gd-orders-title">
		<div class="gd-sec-head">
			<h2 id="gd-orders-title" class="gd-sec-head__h"><?php esc_html_e( 'Recent orders', 'genzee-jewels' ); ?></h2>
			<a class="gd-sec-head__link" href="<?php echo esc_url( $gd_orders_url ); ?>"><?php esc_html_e( 'View all', 'genzee-jewels' ); ?></a>
		</div>

		<?php if ( ! empty( $gd_recent_orders ) ) : ?>
			<div class="gd-order-list">
				<?php foreach ( $gd_recent_orders as $gd_ord ) :
					if ( ! $gd_ord instanceof WC_Abstract_Order ) { continue; }

					$gd_o_items   = $gd_ord->get_items();
					$gd_o_item    = is_array( $gd_o_items ) && ! empty( $gd_o_items ) ? reset( $gd_o_items ) : null;
					$gd_o_name    = $gd_o_item ? $gd_o_item->get_name() : __( 'Order', 'genzee-jewels' );
					$gd_o_img     = '';
					$gd_o_status  = $gd_ord->get_status();
					$gd_o_label   = isset( $gd_status_map[ $gd_o_status ] ) ? $gd_status_map[ $gd_o_status ] : ucfirst( str_replace( '-', ' ', $gd_o_status ) );
					$gd_o_url     = $gd_ord->get_view_order_url();
					$gd_o_total   = $gd_ord->get_formatted_order_total();
					$gd_o_date    = $gd_ord->get_date_created() ? wc_format_datetime( $gd_ord->get_date_created(), 'd M Y' ) : '';
					$gd_o_qty     = 0;
					foreach ( $gd_o_items as $gd_o_line ) {
						$gd_o_qty += $gd_o_line->get_quantity();
					}

					if ( $gd_o_item ) {
						$gd_o_prod = $gd_o_item->get_product();
						if ( $gd_o_prod instanceof WC_Product && $gd_o_prod->get_image_id() ) {
							$gd_o_src = wp_get_attachment_image_url( $gd_o_prod->get_image_id(), 'thumbnail' );
							if ( $gd_o_src ) { $gd_o_img = $gd_o_src; }
						}
					}

					$gd_o_pill_cls = 'gd-pill';
					if ( 'completed' === $gd_o_status ) {
						$gd_o_pill_cls .= ' gd-pill--delivered';
					} elseif ( 'processing' === $gd_o_status ) {
						$gd_o_pill_cls .= ' gd-pill--processing';
					} elseif ( 'on-hold' === $gd_o_status || 'pending' === $gd_o_status ) {
						$gd_o_pill_cls .= ' gd-pill--shipping';
					}
				?>
					<div class="gd-order-row">
						<div class="gd-order-row__thumb" aria-hidden="true">
							<?php if ( $gd_o_img ) : ?>
								<img src="<?php echo esc_url( $gd_o_img ); ?>" alt="" loading="lazy">
							<?php endif; ?>
						</div>
						<div class="gd-order-row__info">
							<p class="gd-order-row__name"><?php echo esc_html( $gd_o_name ); ?></p>
							<p class="gd-order-row__sub"><?php echo esc_html( '#' . $gd_ord->get_order_number() ); ?></p>
						</div>
						<div class="gd-order-row__meta">
							<p class="gd-order-row__meta-lbl"><?php esc_html_e( 'Qty', 'genzee-jewels' ); ?></p>
							<p class="gd-order-row__meta-val"><?php echo esc_html( $gd_o_qty ); ?></p>
						</div>
						<div class="gd-order-row__meta">
							<p class="gd-order-row__meta-lbl"><?php esc_html_e( 'Total', 'genzee-jewels' ); ?></p>
							<p class="gd-order-row__meta-val"><?php echo wp_kses_post( $gd_o_total ); ?></p>
						</div>
						<div class="gd-order-row__meta">
							<p class="gd-order-row__meta-lbl"><?php esc_html_e( 'Status', 'genzee-jewels' ); ?></p>
							<span class="<?php echo esc_attr( $gd_o_pill_cls ); ?>">
								<span class="gd-pill__dot" aria-hidden="true"></span>
								<?php echo esc_html( $gd_o_label ); ?>
							</span>
						</div>
						<div class="gd-order-row__cta">
							<a class="gd-row-cta" href="<?php echo esc_url( $gd_o_url ); ?>">
								<?php esc_html_e( 'View', 'genzee-jewels' ); ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="gd-empty">
				<p class="gd-empty__title"><?php esc_html_e( 'No orders yet', 'genzee-jewels' ); ?></p>
				<p class="gd-empty__sub"><?php esc_html_e( 'Your first order will appear here.', 'genzee-jewels' ); ?></p>
				<a class="gd-btn gd-btn--primary" href="<?php echo esc_url( $gd_shop_url ); ?>"><?php esc_html_e( 'Shop now', 'genzee-jewels' ); ?></a>
			</div>
		<?php endif; ?>
	</section>

	<!-- ── Two-column: Addresses + Wishlist ────────────── -->
	<div class="gd-two-col">

		<section class="gd-panel" aria-labelledby="gd-addr-title">
			<div class="gd-sec-head">
				<h2 id="gd-addr-title" class="gd-sec-head__h"><?php esc_html_e( 'Saved addresses', 'genzee-jewels' ); ?></h2>
				<a class="gd-sec-head__link" href="<?php echo esc_url( $gd_address_url ); ?>"><?php esc_html_e( 'Edit', 'genzee-jewels' ); ?></a>
			</div>
			<p class="gd-panel__sub"><?php esc_html_e( 'Your delivery and billing details.', 'genzee-jewels' ); ?></p>

			<div class="gd-addr-card<?php echo $gd_billing ? ' gd-addr-card--default' : ''; ?>">
				<?php if ( $gd_billing ) : ?>
					<span class="gd-addr-card__tag"><?php esc_html_e( 'Billing', 'genzee-jewels' ); ?></span>
					<address class="gd-addr-card__lines"><?php echo wp_kses_post( $gd_billing ); ?></address>
					<div class="gd-addr-card__actions">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Edit', 'genzee-jewels' ); ?></a>
					</div>
				<?php else : ?>
					<p class="gd-addr-card__empty"><?php esc_html_e( 'No billing address saved.', 'genzee-jewels' ); ?></p>
					<div class="gd-addr-card__actions">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Add', 'genzee-jewels' ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<div class="gd-addr-card">
				<?php if ( $gd_shipping ) : ?>
					<span class="gd-addr-card__tag"><?php esc_html_e( 'Shipping', 'genzee-jewels' ); ?></span>
					<address class="gd-addr-card__lines"><?php echo wp_kses_post( $gd_shipping ); ?></address>
					<div class="gd-addr-card__actions">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Edit', 'genzee-jewels' ); ?></a>
					</div>
				<?php else : ?>
					<p class="gd-addr-card__empty"><?php esc_html_e( 'No shipping address saved.', 'genzee-jewels' ); ?></p>
					<div class="gd-addr-card__actions">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Add', 'genzee-jewels' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<section class="gd-panel" aria-labelledby="gd-wish-title" data-dash-wishlist>
			<div class="gd-sec-head">
				<h2 id="gd-wish-title" class="gd-sec-head__h"><?php esc_html_e( 'From your wishlist', 'genzee-jewels' ); ?></h2>
				<a class="gd-sec-head__link" href="<?php echo esc_url( $gd_wishlist_url ); ?>"><?php esc_html_e( 'View all', 'genzee-jewels' ); ?></a>
			</div>
			<p class="gd-panel__sub"><?php esc_html_e( 'Pieces you saved for later.', 'genzee-jewels' ); ?></p>
			<ul class="gd-wish-grid" data-dash-wishlist-grid aria-label="<?php esc_attr_e( 'Saved products', 'genzee-jewels' ); ?>"></ul>
			<div class="gd-wish-empty" data-dash-wishlist-empty hidden>
				<p><?php esc_html_e( 'No saved styles yet.', 'genzee-jewels' ); ?></p>
				<a class="gd-sec-head__link" href="<?php echo esc_url( $gd_shop_url ); ?>"><?php esc_html_e( 'Start saving', 'genzee-jewels' ); ?></a>
			</div>
		</section>

	</div>

</div>
<?php } catch ( \Throwable $gd_err ) {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<div style="background:#fff3f5;padding:1rem 1.25rem;border:1px solid #c0392b;border-left:4px solid #c0392b;border-radius:8px;margin:1rem 0;font-family:monospace;font-size:13px;">'
			. '<strong>Dashboard PHP error:</strong> ' . esc_html( $gd_err->getMessage() )
			. ' — <em>' . esc_html( basename( $gd_err->getFile() ) ) . ':' . (int) $gd_err->getLine() . '</em>'
			. '</div>';
	}
}
