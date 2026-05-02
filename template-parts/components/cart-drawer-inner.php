<?php
/**
 * Cart drawer - real WooCommerce cart items inner content.
 * Extracted for use in WC AJAX fragments.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$genzee_wc_active    = class_exists( 'WooCommerce' );
$genzee_cart_count   = 0;
$genzee_cart_items   = array();
$genzee_subtotal_raw = 0.0;
$genzee_discount_raw = 0.0;
$genzee_savings_raw  = 0.0;
$genzee_coupons      = array();
$genzee_shipping     = '';
$genzee_checkout_url = home_url( '/checkout/' );
$genzee_shop_url     = home_url( '/shop/' );
$genzee_cross_sell_tiles = array();

if ( $genzee_wc_active && function_exists( 'WC' ) && WC()->cart ) {
	$genzee_cart_count   = (int) WC()->cart->get_cart_contents_count();
	$genzee_cart_items   = WC()->cart->get_cart();
	$genzee_subtotal_raw = (float) WC()->cart->get_subtotal();
	$genzee_discount_raw = (float) WC()->cart->get_discount_total() + (float) WC()->cart->get_discount_tax();
	$genzee_coupons      = WC()->cart->get_applied_coupons();

	foreach ( $genzee_cart_items as $genzee_savings_item ) {
		if ( empty( $genzee_savings_item['data'] ) || ! is_a( $genzee_savings_item['data'], 'WC_Product' ) ) {
			continue;
		}

		$genzee_savings_product = $genzee_savings_item['data'];
		$genzee_savings_qty     = isset( $genzee_savings_item['quantity'] ) ? (int) $genzee_savings_item['quantity'] : 0;
		$genzee_regular_price   = (float) $genzee_savings_product->get_regular_price();
		$genzee_current_price   = (float) $genzee_savings_product->get_price();

		if ( $genzee_regular_price > $genzee_current_price ) {
			$genzee_savings_raw += ( $genzee_regular_price - $genzee_current_price ) * $genzee_savings_qty;
		}
	}

	$genzee_savings_raw += $genzee_discount_raw;
}

if ( function_exists( 'wc_get_checkout_url' ) ) {
	$genzee_checkout_url = wc_get_checkout_url();
}

if ( function_exists( 'wc_get_page_permalink' ) ) {
	$genzee_shop_url = wc_get_page_permalink( 'shop' );
}

$genzee_cart_empty     = empty( $genzee_cart_items );

if ( $genzee_wc_active && function_exists( 'WC' ) && WC()->cart && WC()->cart->needs_shipping() ) {
	$genzee_shipping = WC()->cart->get_cart_shipping_total();
} elseif ( ! $genzee_cart_empty ) {
	$genzee_shipping = __( 'No shipping needed', 'genzee-jewels' );
} else {
	$genzee_shipping = __( 'Calculated at checkout', 'genzee-jewels' );
}

if ( $genzee_wc_active && function_exists( 'wc_get_products' ) ) {
	$genzee_cart_product_ids = array();
	$genzee_cross_sell_ids   = array();

	foreach ( $genzee_cart_items as $genzee_cross_sell_item ) {
		if ( empty( $genzee_cross_sell_item['data'] ) || ! is_a( $genzee_cross_sell_item['data'], 'WC_Product' ) ) {
			continue;
		}

		$genzee_cross_sell_product = $genzee_cross_sell_item['data'];
		$genzee_cart_product_ids[] = (int) $genzee_cross_sell_product->get_id();

		if ( isset( $genzee_cross_sell_item['product_id'] ) ) {
			$genzee_cart_product_ids[] = (int) $genzee_cross_sell_item['product_id'];
		}

		$genzee_cross_sell_ids = array_merge(
			$genzee_cross_sell_ids,
			array_map( 'absint', $genzee_cross_sell_product->get_cross_sell_ids() )
		);
	}

	$genzee_cart_product_ids = array_values( array_unique( array_filter( $genzee_cart_product_ids ) ) );
	$genzee_cross_sell_ids   = array_values( array_diff( array_unique( array_filter( $genzee_cross_sell_ids ) ), $genzee_cart_product_ids ) );

	if ( ! empty( $genzee_cross_sell_ids ) ) {
		$genzee_cross_sell_products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 4,
				'include' => $genzee_cross_sell_ids,
				'exclude' => $genzee_cart_product_ids,
				'return'  => 'objects',
			)
		);

		foreach ( $genzee_cross_sell_products as $genzee_cross_sell_product ) {
			if ( is_a( $genzee_cross_sell_product, 'WC_Product' ) ) {
				$genzee_cross_sell_tiles[] = array( 'product' => $genzee_cross_sell_product );
			}
		}
	}

	if ( count( $genzee_cross_sell_tiles ) < 4 ) {
		$genzee_existing_cross_sell_ids = array();
		foreach ( $genzee_cross_sell_tiles as $genzee_cross_sell_tile ) {
			if ( ! empty( $genzee_cross_sell_tile['product'] ) && is_a( $genzee_cross_sell_tile['product'], 'WC_Product' ) ) {
				$genzee_existing_cross_sell_ids[] = (int) $genzee_cross_sell_tile['product']->get_id();
			}
		}

		$genzee_candidate_products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 12,
				'orderby' => 'date',
				'order'   => 'DESC',
				'exclude' => array_merge( $genzee_cart_product_ids, $genzee_existing_cross_sell_ids ),
				'return'  => 'objects',
			)
		);

		usort(
			$genzee_candidate_products,
			static function ( $genzee_a, $genzee_b ) {
				$genzee_a_price = is_a( $genzee_a, 'WC_Product' ) ? (float) $genzee_a->get_price() : 999999;
				$genzee_b_price = is_a( $genzee_b, 'WC_Product' ) ? (float) $genzee_b->get_price() : 999999;

				if ( $genzee_a_price == $genzee_b_price ) {
					return 0;
				}
				return ( $genzee_a_price < $genzee_b_price ) ? -1 : 1;
			}
		);

		foreach ( $genzee_candidate_products as $genzee_candidate_product ) {
			if ( count( $genzee_cross_sell_tiles ) >= 4 ) {
				break;
			}

			if ( is_a( $genzee_candidate_product, 'WC_Product' ) ) {
				$genzee_cross_sell_tiles[] = array( 'product' => $genzee_candidate_product );
			}
		}
	}
}

?>

<div id="cart-drawer-inner" class="genzee-cart-drawer-content">
	<?php if ( $genzee_wc_active ) : ?>

		<div class="cart-drawer__ship-bar" id="cart-drawer-ship-bar">
			<p class="cart-drawer__ship-msg" id="cart-drawer-ship-msg">
				<?php esc_html_e( 'Shipping, coupons, and payment options are confirmed by WooCommerce at checkout.', 'genzee-jewels' ); ?>
			</p>
		</div>

		<div class="cart-drawer__body" id="cart-drawer-body">

			<?php if ( $genzee_cart_empty ) : ?>

				<div class="cart-drawer__empty" id="cart-drawer-empty">
					<svg class="cart-drawer__empty-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
						<path d="M5 7h14l-1.5 11Q17 20 15 20H9Q7 20 6.5 18Z"/>
						<path d="M9 7Q9 3 12 3Q15 3 15 7"/>
					</svg>
					<p class="cart-drawer__empty-msg">
						<?php esc_html_e( 'Your bag is empty.', 'genzee-jewels' ); ?>
					</p>
					<p class="cart-drawer__empty-sub">
						<?php esc_html_e( 'Add something cute before the drop sells out.', 'genzee-jewels' ); ?>
					</p>
					<a
						href="<?php echo esc_url( $genzee_shop_url ); ?>"
						class="cart-drawer__shop-link"
					>
						<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
					</a>
				</div>

				<?php if ( ! empty( $genzee_cross_sell_tiles ) ) : ?>
					<div class="cart-drawer__cross-sell-panel" aria-label="<?php esc_attr_e( 'Mini picks', 'genzee-jewels' ); ?>">
						<p class="cart-drawer__cross-sell-title">
							<?php esc_html_e( 'Tiny treats to browse', 'genzee-jewels' ); ?>
						</p>
						<div class="cart-drawer__cross-sells">
							<?php
							foreach ( $genzee_cross_sell_tiles as $genzee_cross_sell_tile ) {
								get_template_part( 'template-parts/components/cart-mini-product', null, $genzee_cross_sell_tile );
							}
							?>
						</div>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<div class="cart-drawer__items-form">
					<?php foreach ( $genzee_cart_items as $genzee_item_key => $genzee_item ) :
						/** @var WC_Product $genzee_product */
						$genzee_product = $genzee_item['data'];

						if ( ! $genzee_product || ! $genzee_product->exists() ) {
							continue;
						}

						$genzee_qty        = (int) $genzee_item['quantity'];
						$genzee_title      = $genzee_product->get_name();
						$genzee_price      = (float) $genzee_product->get_price();
						$genzee_reg_price  = (float) $genzee_product->get_regular_price();
						$genzee_is_on_sale = $genzee_product->is_on_sale() && $genzee_reg_price > $genzee_price;
						$genzee_remove_url = wc_get_cart_remove_url( $genzee_item_key );
						$genzee_product_id = (int) $genzee_item['product_id'];

						$genzee_cat_raw  = wc_get_product_category_list( $genzee_product_id );
						$genzee_cat_text = $genzee_cat_raw ? wp_strip_all_tags( $genzee_cat_raw ) : '';

						$genzee_img_html = $genzee_product->get_image(
							'woocommerce_thumbnail',
							array(
								'class'   => 'cart-drawer__item-img',
								'loading' => 'lazy',
								'alt'     => esc_attr( $genzee_title ),
							)
						);

						$genzee_var_pairs = array();
						if ( ! empty( $genzee_item['variation'] ) ) {
							foreach ( $genzee_item['variation'] as $genzee_attr => $genzee_val ) {
								if ( '' === $genzee_val ) {
									continue;
								}
								$genzee_attr_clean = str_replace( 'attribute_', '', $genzee_attr );
								$genzee_attr_label = wc_attribute_label( $genzee_attr_clean );
								$genzee_term       = get_term_by( 'slug', $genzee_val, $genzee_attr_clean );
								$genzee_val_label  = $genzee_term ? $genzee_term->name : ucfirst( $genzee_val );
								$genzee_var_pairs[] = esc_html( $genzee_attr_label ) . ': ' . esc_html( $genzee_val_label );
							}
						}
					?>

					<div class="cart-drawer__item woocommerce-mini-cart-item mini_cart_item" data-item-key="<?php echo esc_attr( $genzee_item_key ); ?>">

						<div class="cart-drawer__item-img-wrap">
							<?php echo wp_kses_post( $genzee_img_html ); ?>
						</div>

						<div class="cart-drawer__item-body">

							<?php if ( $genzee_cat_text ) : ?>
								<span class="cart-drawer__item-cat">
									<?php echo esc_html( $genzee_cat_text ); ?>
								</span>
							<?php endif; ?>

							<p class="cart-drawer__item-title">
								<?php echo esc_html( $genzee_title ); ?>
							</p>

							<?php if ( ! empty( $genzee_var_pairs ) ) : ?>
								<p class="cart-drawer__item-meta">
									<?php echo implode( ' &bull; ', $genzee_var_pairs ); ?>
								</p>
							<?php endif; ?>

							<div class="cart-drawer__item-row">

								<div class="cart-drawer__qty" aria-label="<?php esc_attr_e( 'Quantity', 'genzee-jewels' ); ?>">
									<button type="button" class="cart-drawer__qty-btn cart-drawer__qty-btn--minus" data-item-key="<?php echo esc_attr( $genzee_item_key ); ?>" aria-label="<?php esc_attr_e( 'Decrease quantity', 'genzee-jewels' ); ?>">−</button>
									<input
										type="number"
										step="1"
										min="0"
										max="<?php echo esc_attr( $genzee_product->get_max_purchase_quantity() ); ?>"
										name="cart[<?php echo esc_attr( $genzee_item_key ); ?>][qty]"
										value="<?php echo esc_attr( $genzee_qty ); ?>"
										title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'genzee-jewels' ); ?>"
										class="cart-drawer__qty-num qty"
										aria-label="<?php esc_attr_e( 'Product quantity', 'genzee-jewels' ); ?>"
										readonly
									/>
									<button type="button" class="cart-drawer__qty-btn cart-drawer__qty-btn--plus" data-item-key="<?php echo esc_attr( $genzee_item_key ); ?>" aria-label="<?php esc_attr_e( 'Increase quantity', 'genzee-jewels' ); ?>">+</button>
								</div>

								<div class="cart-drawer__item-prices">
									<span class="cart-drawer__item-price<?php echo $genzee_is_on_sale ? ' cart-drawer__item-price--sale' : ''; ?>">
										<?php echo wp_kses_post( wc_price( $genzee_price * $genzee_qty ) ); ?>
									</span>
									<?php if ( $genzee_is_on_sale ) : ?>
										<span class="cart-drawer__item-price-was">
											<?php echo wp_kses_post( wc_price( $genzee_reg_price * $genzee_qty ) ); ?>
										</span>
									<?php endif; ?>
								</div>

							</div><!-- .cart-drawer__item-row -->

						</div><!-- .cart-drawer__item-body -->

						<a
							href="<?php echo esc_url( $genzee_remove_url ); ?>"
							class="cart-drawer__item-remove remove remove_from_cart_button"
							aria-label="<?php esc_attr_e( 'Remove item from bag', 'genzee-jewels' ); ?>"
							data-cart_item_key="<?php echo esc_attr( $genzee_item_key ); ?>"
							data-product_id="<?php echo esc_attr( $genzee_product_id ); ?>"
							data-product_sku="<?php echo esc_attr( $genzee_product->get_sku() ); ?>"
						>
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" focusable="false">
								<path d="M18 6L6 18M6 6l12 12"/>
							</svg>
						</a>

					</div><!-- .cart-drawer__item -->

					<?php endforeach; ?>

				</div>

				<div class="cart-drawer__freebie" aria-label="<?php esc_attr_e( 'More products to consider', 'genzee-jewels' ); ?>">
					<div class="cart-drawer__freebie-head">
						<div class="cart-drawer__freebie-icon" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" focusable="false">
								<path d="M20 12v8H4v-8"/>
								<path d="M2 7h20v5H2z"/>
								<path d="M12 22V7"/>
								<path d="M12 7H7.5A2.5 2.5 0 1 1 10 4.5c0 1.4 2 2.5 2 2.5Z"/>
								<path d="M12 7h4.5A2.5 2.5 0 1 0 14 4.5c0 1.4-2 2.5-2 2.5Z"/>
							</svg>
						</div>
						<div>
							<p class="cart-drawer__freebie-title">
								<?php esc_html_e( 'More pieces to consider', 'genzee-jewels' ); ?>
							</p>
							<p class="cart-drawer__freebie-copy">
								<?php esc_html_e( 'Browse real products from the catalog before checkout.', 'genzee-jewels' ); ?>
							</p>
						</div>
					</div>
					<?php if ( ! empty( $genzee_cross_sell_tiles ) ) : ?>
						<div class="cart-drawer__cross-sells">
							<?php
							foreach ( $genzee_cross_sell_tiles as $genzee_cross_sell_tile ) {
								get_template_part( 'template-parts/components/cart-mini-product', null, $genzee_cross_sell_tile );
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<form
					class="cart-drawer__coupon"
					method="post"
					action="<?php echo esc_url( wc_get_cart_url() ); ?>"
				>
					<label for="cart-drawer-coupon-code" class="cart-drawer__coupon-label">
						<?php esc_html_e( 'COUPON', 'genzee-jewels' ); ?>
					</label>
					<div class="cart-drawer__coupon-row">
						<input
							type="text"
							id="cart-drawer-coupon-code"
							name="coupon_code"
							class="cart-drawer__coupon-input"
							placeholder="<?php esc_attr_e( 'Got a code?', 'genzee-jewels' ); ?>"
						/>
						<button type="submit" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'genzee-jewels' ); ?>" class="cart-drawer__coupon-apply">
							<?php esc_html_e( 'Apply', 'genzee-jewels' ); ?>
						</button>
					</div>
					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</form>

			<?php endif; ?>

		</div><!-- .cart-drawer__body -->

		<?php if ( ! $genzee_cart_empty ) : ?>

		<div class="cart-drawer__footer" id="cart-drawer-footer">
			<div class="cart-drawer__summary" id="cart-drawer-summary">
				<div class="cart-drawer__summary-row">
					<span><?php esc_html_e( 'Subtotal', 'genzee-jewels' ); ?></span>
					<span class="cart-drawer__summary-val" id="cart-drawer-subtotal">
						<?php
						if ( function_exists( 'WC' ) && WC()->cart ) {
							echo wp_kses_post( WC()->cart->get_cart_subtotal() );
						}
						?>
					</span>
				</div>
				<div class="cart-drawer__summary-row">
					<span><?php esc_html_e( 'Shipping', 'genzee-jewels' ); ?></span>
					<span class="cart-drawer__summary-val">
						<?php echo wp_kses_post( $genzee_shipping ); ?>
					</span>
				</div>
				<?php if ( $genzee_discount_raw > 0 ) : ?>
					<div class="cart-drawer__summary-row cart-drawer__summary-row--discount">
						<span>
							<?php
							if ( ! empty( $genzee_coupons ) ) {
								echo esc_html(
									sprintf(
										/* translators: %s: applied coupon codes */
										__( 'Discount (%s)', 'genzee-jewels' ),
										implode( ', ', array_map( 'wc_format_coupon_code', $genzee_coupons ) )
									)
								);
							} else {
								esc_html_e( 'Discount', 'genzee-jewels' );
							}
							?>
						</span>
						<span class="cart-drawer__summary-val">
							-<?php echo wp_kses_post( wc_price( $genzee_discount_raw ) ); ?>
						</span>
					</div>
				<?php endif; ?>
				<div class="cart-drawer__summary-total">
					<span><?php esc_html_e( 'Total', 'genzee-jewels' ); ?></span>
					<strong>
						<?php
						if ( function_exists( 'WC' ) && WC()->cart ) {
							echo wp_kses_post( WC()->cart->get_total() );
						}
						?>
					</strong>
				</div>
				<?php if ( $genzee_savings_raw > 0 ) : ?>
					<p class="cart-drawer__savings">
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

			<a
				href="<?php echo esc_url( $genzee_checkout_url ); ?>"
				class="cart-drawer__checkout"
			>
				<?php esc_html_e( 'Checkout securely', 'genzee-jewels' ); ?>
			</a>

			<ul
				class="cart-drawer__payment-badges"
				aria-label="<?php esc_attr_e( 'Accepted payment methods', 'genzee-jewels' ); ?>"
			>
				<?php
				$genzee_payment_methods = array(
					__( 'Secure checkout', 'genzee-jewels' ),
					__( 'Payment options at checkout', 'genzee-jewels' ),
				);
				foreach ( $genzee_payment_methods as $genzee_pm ) :
				?>
				<li><?php echo esc_html( $genzee_pm ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div><!-- .cart-drawer__footer -->

		<?php endif; ?>

	<?php else : ?>

		<div class="cart-drawer__body">
			<div class="cart-drawer__wc-inactive">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
					<circle cx="12" cy="12" r="10"/>
					<path d="M12 8v4M12 16h.01"/>
				</svg>
				<p>
					<?php esc_html_e( 'Shopping cart is not available right now. Please check back soon.', 'genzee-jewels' ); ?>
				</p>
			</div>
		</div>

	<?php endif; ?>
</div>
