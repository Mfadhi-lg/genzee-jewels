<?php
/**
 * Cart page template.
 *
 * @package genzee-jewels
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

$genzee_cart          = WC()->cart;
$genzee_cart_items    = $genzee_cart ? $genzee_cart->get_cart() : array();
$genzee_cart_count    = $genzee_cart ? (int) $genzee_cart->get_cart_contents_count() : 0;
$genzee_subtotal_raw  = $genzee_cart ? (float) $genzee_cart->get_subtotal() : 0.0;
$genzee_discount_raw  = $genzee_cart ? (float) $genzee_cart->get_discount_total() + (float) $genzee_cart->get_discount_tax() : 0.0;
$genzee_coupons       = $genzee_cart ? $genzee_cart->get_applied_coupons() : array();
$genzee_shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$genzee_checkout_url  = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
$genzee_free_target   = 499;
$genzee_to_free       = max( 0.0, $genzee_free_target - $genzee_subtotal_raw );
$genzee_progress      = (int) min( 100, $genzee_free_target > 0 ? ( $genzee_subtotal_raw / $genzee_free_target ) * 100 : 0 );
$genzee_cart_empty    = ! $genzee_cart || $genzee_cart->is_empty();
$genzee_savings_raw   = $genzee_discount_raw;
$genzee_fallback_tiles    = array();
$genzee_cart_product_ids  = array();

foreach ( $genzee_cart_items as $genzee_savings_item ) {
	if ( empty( $genzee_savings_item['data'] ) || ! is_a( $genzee_savings_item['data'], 'WC_Product' ) ) {
		continue;
	}

	$genzee_savings_product = $genzee_savings_item['data'];
	$genzee_savings_qty     = isset( $genzee_savings_item['quantity'] ) ? (int) $genzee_savings_item['quantity'] : 0;
	$genzee_regular_price   = (float) $genzee_savings_product->get_regular_price();
	$genzee_current_price   = (float) $genzee_savings_product->get_price();
	$genzee_cart_product_ids[] = (int) $genzee_savings_product->get_id();

	if ( isset( $genzee_savings_item['product_id'] ) ) {
		$genzee_cart_product_ids[] = (int) $genzee_savings_item['product_id'];
	}

	if ( $genzee_regular_price > $genzee_current_price ) {
		$genzee_savings_raw += ( $genzee_regular_price - $genzee_current_price ) * $genzee_savings_qty;
	}
}

$genzee_cart_product_ids = array_values( array_unique( array_filter( $genzee_cart_product_ids ) ) );

if ( function_exists( 'wc_get_products' ) ) {
	$genzee_fallback_products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 4,
			'orderby' => 'date',
			'order'   => 'DESC',
			'exclude' => $genzee_cart_product_ids,
			'return'  => 'objects',
		)
	);

	foreach ( $genzee_fallback_products as $genzee_fallback_product ) {
		if ( is_a( $genzee_fallback_product, 'WC_Product' ) ) {
			$genzee_fallback_tiles[] = array( 'product' => $genzee_fallback_product );
		}
	}
}

$genzee_demo_tiles = array(
	array(
		'id'    => 'cart-page-demo-199',
		'name'  => __( 'Cherry Studs', 'genzee-jewels' ),
		'price' => 199,
		'mrp'   => 399,
		'color' => '#FF2D6F',
		'link'  => $genzee_shop_url,
	),
	array(
		'id'    => 'cart-page-demo-349',
		'name'  => __( 'Chrome Hoops', 'genzee-jewels' ),
		'price' => 349,
		'mrp'   => 699,
		'color' => '#C9B6FF',
		'link'  => $genzee_shop_url,
	),
	array(
		'id'    => 'cart-page-demo-449',
		'name'  => __( 'Star Choker', 'genzee-jewels' ),
		'price' => 449,
		'mrp'   => 899,
		'color' => '#FFE14A',
		'link'  => $genzee_shop_url,
	),
	array(
		'id'    => 'cart-page-demo-399',
		'name'  => __( 'Dainty Chain', 'genzee-jewels' ),
		'price' => 399,
		'mrp'   => 799,
		'color' => '#B8F2C9',
		'link'  => $genzee_shop_url,
	),
);

foreach ( $genzee_demo_tiles as $genzee_demo_tile ) {
	if ( count( $genzee_fallback_tiles ) >= 4 ) {
		break;
	}

	$genzee_fallback_tiles[] = array( 'demo' => $genzee_demo_tile );
}
?>

<section class="cart-page" id="primary">
	<div class="cart-page__inner">
		<header class="cart-page__header">
			<div>
				<p class="cart-page__eyebrow"><?php esc_html_e( 'Secure checkout ready', 'genzee-jewels' ); ?></p>
				<h1 class="cart-page__title">
					<?php esc_html_e( 'Your bag', 'genzee-jewels' ); ?>
					<?php if ( $genzee_cart_count > 0 ) : ?>
						<span>(<?php echo esc_html( $genzee_cart_count ); ?>)</span>
					<?php endif; ?>
				</h1>
			</div>
			<a class="cart-page__continue" href="<?php echo esc_url( $genzee_shop_url ); ?>">
				<?php esc_html_e( 'Continue shopping', 'genzee-jewels' ); ?>
			</a>
		</header>

		<?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
			<div class="cart-page__notices">
				<?php woocommerce_output_all_notices(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $genzee_cart_empty ) : ?>
			<section class="cart-page__empty" aria-label="<?php esc_attr_e( 'Empty cart', 'genzee-jewels' ); ?>">
				<svg class="cart-page__empty-icon" width="58" height="58" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
					<path d="M5 7h14l-1.5 11Q17 20 15 20H9Q7 20 6.5 18Z"/>
					<path d="M9 7Q9 3 12 3Q15 3 15 7"/>
				</svg>
				<h2><?php esc_html_e( 'Your bag is empty.', 'genzee-jewels' ); ?></h2>
				<a class="cart-page__primary-link" href="<?php echo esc_url( $genzee_shop_url ); ?>">
					<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
				</a>
			</section>

			<?php if ( ! empty( $genzee_fallback_tiles ) ) : ?>
				<section class="cart-page__fallback" aria-label="<?php esc_attr_e( 'New products', 'genzee-jewels' ); ?>">
					<h2><?php esc_html_e( 'Fresh picks under 499', 'genzee-jewels' ); ?></h2>
					<div class="cart-page__mini-grid">
						<?php
						foreach ( $genzee_fallback_tiles as $genzee_fallback_tile ) {
							get_template_part( 'template-parts/components/cart-mini-product', null, $genzee_fallback_tile );
						}
						?>
					</div>
				</section>
			<?php endif; ?>
		<?php else : ?>
			<div class="cart-page__layout">
				<section class="cart-page__items-panel" aria-label="<?php esc_attr_e( 'Cart items', 'genzee-jewels' ); ?>">
					<div class="cart-page__ship-bar">
						<?php if ( $genzee_to_free > 0 ) : ?>
							<p>
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: %s: remaining amount for free shipping */
										__( 'Add %s more for FREE shipping', 'genzee-jewels' ),
										'<strong>' . wc_price( $genzee_to_free ) . '</strong>'
									)
								);
								?>
							</p>
						<?php else : ?>
							<p class="cart-page__ship-unlocked"><?php esc_html_e( 'FREE shipping unlocked!', 'genzee-jewels' ); ?></p>
						<?php endif; ?>
						<progress class="cart-page__progress" value="<?php echo esc_attr( $genzee_progress ); ?>" max="100" aria-label="<?php esc_attr_e( 'Free shipping progress', 'genzee-jewels' ); ?>"></progress>
					</div>

					<form class="woocommerce-cart-form cart-page__form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
						<?php do_action( 'woocommerce_before_cart_table' ); ?>

						<div class="cart-page__items">
							<?php do_action( 'woocommerce_before_cart_contents' ); ?>

							<?php
							foreach ( $genzee_cart_items as $genzee_cart_item_key => $genzee_cart_item ) :
								$genzee_product = apply_filters( 'woocommerce_cart_item_product', $genzee_cart_item['data'], $genzee_cart_item, $genzee_cart_item_key );
								$genzee_product_id = apply_filters( 'woocommerce_cart_item_product_id', $genzee_cart_item['product_id'], $genzee_cart_item, $genzee_cart_item_key );

								if ( ! $genzee_product || ! $genzee_product->exists() || $genzee_cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $genzee_cart_item, $genzee_cart_item_key ) ) {
									continue;
								}

								$genzee_product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $genzee_product->is_visible() ? $genzee_product->get_permalink( $genzee_cart_item ) : '', $genzee_cart_item, $genzee_cart_item_key );
								$genzee_product_name      = apply_filters( 'woocommerce_cart_item_name', $genzee_product->get_name(), $genzee_cart_item, $genzee_cart_item_key );
								$genzee_thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $genzee_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'cart-page__item-img' ) ), $genzee_cart_item, $genzee_cart_item_key );
								$genzee_subtotal          = apply_filters( 'woocommerce_cart_item_subtotal', $genzee_cart->get_product_subtotal( $genzee_product, $genzee_cart_item['quantity'] ), $genzee_cart_item, $genzee_cart_item_key );
								$genzee_cat_raw           = wc_get_product_category_list( $genzee_product_id );
								$genzee_cat_text          = $genzee_cat_raw ? wp_strip_all_tags( $genzee_cat_raw ) : '';
								?>

								<article class="cart-page__item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $genzee_cart_item, $genzee_cart_item_key ) ); ?>">
									<a class="cart-page__item-media" href="<?php echo esc_url( $genzee_product_permalink ? $genzee_product_permalink : $genzee_shop_url ); ?>">
										<?php echo wp_kses_post( $genzee_thumbnail ); ?>
									</a>

									<div class="cart-page__item-main">
										<?php if ( $genzee_cat_text ) : ?>
											<span class="cart-page__item-cat"><?php echo esc_html( $genzee_cat_text ); ?></span>
										<?php endif; ?>

										<h2 class="cart-page__item-title">
											<?php if ( $genzee_product_permalink ) : ?>
												<a href="<?php echo esc_url( $genzee_product_permalink ); ?>"><?php echo wp_kses_post( $genzee_product_name ); ?></a>
											<?php else : ?>
												<?php echo wp_kses_post( $genzee_product_name ); ?>
											<?php endif; ?>
										</h2>

										<?php if ( wc_get_formatted_cart_item_data( $genzee_cart_item ) ) : ?>
											<div class="cart-page__item-meta">
												<?php echo wp_kses_post( wc_get_formatted_cart_item_data( $genzee_cart_item ) ); ?>
											</div>
										<?php endif; ?>

										<div class="cart-page__item-mobile-prices">
											<strong><?php echo wp_kses_post( $genzee_subtotal ); ?></strong>
										</div>
									</div>

									<div class="cart-page__item-qty">
										<?php
										if ( $genzee_product->is_sold_individually() ) {
											$genzee_min_quantity = 1;
											$genzee_max_quantity = 1;
										} else {
											$genzee_min_quantity = 0;
											$genzee_max_quantity = $genzee_product->get_max_purchase_quantity();
										}

										?>
										<div class="cart-page__qty-stepper">
											<button
												type="button"
												class="cart-page__qty-step cart-page__qty-step--minus"
												aria-label="<?php esc_attr_e( 'Decrease quantity', 'genzee-jewels' ); ?>"
											>-</button>
											<?php
											echo woocommerce_quantity_input(
												array(
													'input_name'   => "cart[{$genzee_cart_item_key}][qty]",
													'input_value'  => $genzee_cart_item['quantity'],
													'max_value'    => $genzee_max_quantity,
													'min_value'    => $genzee_min_quantity,
													'product_name' => $genzee_product->get_name(),
												),
												$genzee_product,
												false
											);
											?>
											<button
												type="button"
												class="cart-page__qty-step cart-page__qty-step--plus"
												aria-label="<?php esc_attr_e( 'Increase quantity', 'genzee-jewels' ); ?>"
											>+</button>
										</div>
									</div>

									<div class="cart-page__item-subtotal" aria-label="<?php esc_attr_e( 'Subtotal', 'genzee-jewels' ); ?>">
										<?php echo wp_kses_post( $genzee_subtotal ); ?>
									</div>

									<?php
									echo apply_filters(
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="cart-page__item-remove remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span aria-hidden="true">&times;</span></a>',
											esc_url( wc_get_cart_remove_url( $genzee_cart_item_key ) ),
											esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $genzee_product_name ) ) ),
											esc_attr( $genzee_product_id ),
											esc_attr( $genzee_product->get_sku() )
										),
										$genzee_cart_item_key
									);
									?>
								</article>
							<?php endforeach; ?>

							<?php do_action( 'woocommerce_cart_contents' ); ?>
						</div>

						<div class="cart-page__form-actions">
							<?php if ( wc_coupons_enabled() ) : ?>
								<div class="cart-page__coupon coupon">
									<label for="coupon_code" class="cart-page__coupon-label"><?php esc_html_e( 'COUPON', 'genzee-jewels' ); ?></label>
									<div class="cart-page__coupon-row">
										<input type="text" name="coupon_code" class="input-text cart-page__coupon-input" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Got a code?', 'genzee-jewels' ); ?>" />
										<button type="submit" class="cart-page__coupon-apply" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'genzee-jewels' ); ?></button>
									</div>
									<div class="cart-page__coupon-chips" aria-label="<?php esc_attr_e( 'Suggested coupon codes', 'genzee-jewels' ); ?>">
										<span>GENZ50</span>
										<span>FIRST15</span>
										<span>COMBO3</span>
									</div>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div>
							<?php endif; ?>

							<button type="submit" class="cart-page__update" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
								<?php esc_html_e( 'Update bag', 'genzee-jewels' ); ?>
							</button>
						</div>

						<?php do_action( 'woocommerce_cart_actions' ); ?>
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						<?php do_action( 'woocommerce_after_cart_table' ); ?>
					</form>

					<div class="cart-page__freebie" aria-label="<?php esc_attr_e( 'Freebie offer', 'genzee-jewels' ); ?>">
						<div class="cart-page__freebie-head">
							<div class="cart-page__freebie-icon" aria-hidden="true">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" focusable="false">
									<path d="M20 12v8H4v-8"/>
									<path d="M2 7h20v5H2z"/>
									<path d="M12 22V7"/>
									<path d="M12 7H7.5A2.5 2.5 0 1 1 10 4.5c0 1.4 2 2.5 2 2.5Z"/>
									<path d="M12 7h4.5A2.5 2.5 0 1 0 14 4.5c0 1.4-2 2.5-2 2.5Z"/>
								</svg>
							</div>
							<div>
								<p class="cart-page__freebie-title"><?php esc_html_e( 'Add 1 more item - get a FREE nose pin', 'genzee-jewels' ); ?></p>
								<p class="cart-page__freebie-copy"><?php esc_html_e( 'Valued at 149. Auto-added at checkout when eligible.', 'genzee-jewels' ); ?></p>
							</div>
						</div>

						<?php if ( ! empty( $genzee_fallback_tiles ) ) : ?>
							<div class="cart-page__freebie-grid">
								<?php
								foreach ( array_slice( $genzee_fallback_tiles, 0, 4 ) as $genzee_freebie_tile ) {
									get_template_part( 'template-parts/components/cart-mini-product', null, $genzee_freebie_tile );
								}
								?>
							</div>
						<?php endif; ?>
					</div>
				</section>

				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

				<aside class="cart-page__summary" aria-label="<?php esc_attr_e( 'Cart totals', 'genzee-jewels' ); ?>">
					<h2><?php esc_html_e( 'Order summary', 'genzee-jewels' ); ?></h2>
					<div class="cart-page__summary-rows">
						<div class="cart-page__summary-row">
							<span><?php esc_html_e( 'Subtotal', 'genzee-jewels' ); ?></span>
							<strong><?php echo wp_kses_post( $genzee_cart->get_cart_subtotal() ); ?></strong>
						</div>
						<div class="cart-page__summary-row">
							<span><?php esc_html_e( 'Shipping', 'genzee-jewels' ); ?></span>
							<strong class="<?php echo 0.0 === $genzee_to_free ? 'is-success' : ''; ?>">
								<?php
								if ( 0.0 === $genzee_to_free ) {
									esc_html_e( 'FREE', 'genzee-jewels' );
								} elseif ( $genzee_cart->needs_shipping() ) {
									echo wp_kses_post( $genzee_cart->get_cart_shipping_total() );
								} else {
									esc_html_e( 'No shipping needed', 'genzee-jewels' );
								}
								?>
							</strong>
						</div>
						<?php foreach ( $genzee_coupons as $genzee_code ) : ?>
							<div class="cart-page__summary-row cart-page__summary-row--discount">
								<span>
									<?php
									echo esc_html(
										sprintf(
											/* translators: %s: coupon code */
											__( 'Coupon (%s)', 'genzee-jewels' ),
											wc_format_coupon_code( $genzee_code )
										)
									);
									?>
								</span>
								<strong><?php wc_cart_totals_coupon_html( $genzee_code ); ?></strong>
							</div>
						<?php endforeach; ?>
						<?php if ( $genzee_discount_raw > 0 && empty( $genzee_coupons ) ) : ?>
							<div class="cart-page__summary-row cart-page__summary-row--discount">
								<span><?php esc_html_e( 'Discount', 'genzee-jewels' ); ?></span>
								<strong>-<?php echo wp_kses_post( wc_price( $genzee_discount_raw ) ); ?></strong>
							</div>
						<?php endif; ?>
						<div class="cart-page__summary-total">
							<span><?php esc_html_e( 'Total', 'genzee-jewels' ); ?></span>
							<strong><?php echo wp_kses_post( $genzee_cart->get_total() ); ?></strong>
						</div>
						<?php if ( $genzee_savings_raw > 0 ) : ?>
							<p class="cart-page__savings">
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: %s: savings amount */
										__( 'You save %s today', 'genzee-jewels' ),
										wc_price( $genzee_savings_raw )
									)
								);
								?>
							</p>
						<?php endif; ?>
					</div>

					<a class="cart-page__checkout" href="<?php echo esc_url( $genzee_checkout_url ); ?>">
						<?php esc_html_e( 'Checkout securely', 'genzee-jewels' ); ?>
					</a>
					<ul class="cart-page__payment-badges" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'genzee-jewels' ); ?>">
						<li>UPI</li>
						<li>VISA</li>
						<li>MC</li>
						<li>COD</li>
					</ul>
					<p class="cart-page__trust"><?php esc_html_e( 'UPI / VISA / MC / COD accepted. No custom checkout logic.', 'genzee-jewels' ); ?></p>
					<?php do_action( 'woocommerce_after_cart_totals' ); ?>
				</aside>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
