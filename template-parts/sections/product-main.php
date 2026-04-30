<?php
/**
 * PDP main section: gallery and purchase panel.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = isset( $args ) && is_array( $args ) ? $args : array();
$product = isset( $args['product'] ) && $args['product'] instanceof WC_Product ? $args['product'] : null;

if ( ! $product ) {
	return;
}

$product_id       = (int) $product->get_id();
$product_name     = $product->get_name();
$featured_id      = absint( $product->get_image_id() );
$gallery_ids      = array_filter( array_map( 'absint', $product->get_gallery_image_ids() ) );
$image_ids        = $featured_id ? array_merge( array( $featured_id ), $gallery_ids ) : $gallery_ids;
$main_image_id    = ! empty( $image_ids ) ? absint( $image_ids[0] ) : 0;
$average_rating   = (float) $product->get_average_rating();
$review_count     = (int) $product->get_review_count();
$category_terms   = get_the_terms( $product_id, 'product_cat' );
$primary_category = ! is_wp_error( $category_terms ) && ! empty( $category_terms ) ? reset( $category_terms ) : null;
$category_label   = $primary_category instanceof WP_Term ? $primary_category->name : __( 'Genzee Jewels', 'genzee-jewels' );
$category_url     = $primary_category instanceof WP_Term ? get_term_link( $primary_category ) : '';
$regular_price    = $product->is_type( 'variable' ) ? (float) $product->get_variation_regular_price( 'min', true ) : (float) $product->get_regular_price();
$sale_price       = $product->is_type( 'variable' ) ? (float) $product->get_variation_sale_price( 'min', true ) : (float) $product->get_sale_price();
$is_on_sale       = $product->is_on_sale() && $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price;
$discount_percent = $is_on_sale ? (int) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ) : 0;
$stock_quantity   = $product->managing_stock() ? $product->get_stock_quantity() : null;
$is_low_stock     = null !== $stock_quantity && $stock_quantity > 0 && $stock_quantity <= 5;
$pdp_badge        = (string) $product->get_meta( 'genzee_badge', true );
if ( '' === $pdp_badge && $is_low_stock ) {
	$pdp_badge = __( 'LOW STOCK', 'genzee-jewels' );
}
$pdp_badge_upper  = strtoupper( $pdp_badge );
$pdp_badge_class  = ( 'BESTSELLER' === $pdp_badge_upper || 'LOW STOCK' === $pdp_badge_upper || false !== strpos( $pdp_badge_upper, 'TRENDING' ) )
	? 'pdp-main__badge pdp-main__badge--hot'
	: 'pdp-main__badge pdp-main__badge--butter';
$attributes       = array();
$bundle_products  = array();
$bundle_items     = array();
$bundle_total     = 0.0;
$bundle_regular   = 0.0;
$bundle_save      = 0.0;
$buy_now_url      = '#pdp-product-title';

foreach ( $product->get_attributes() as $attribute ) {
	if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_visible() ) {
		continue;
	}

	$options = array();

	if ( $attribute->is_taxonomy() ) {
		$terms = wc_get_product_terms( $product_id, $attribute->get_name(), array( 'fields' => 'names' ) );
		$options = is_array( $terms ) ? $terms : array();
	} else {
		$options = $attribute->get_options();
	}

	$options = array_values( array_filter( array_map( 'wc_clean', $options ) ) );

	if ( ! empty( $options ) ) {
		$attributes[] = array(
			'label'   => wc_attribute_label( $attribute->get_name() ),
			'options' => array_slice( $options, 0, 4 ),
		);
	}

	if ( ! empty( $attributes ) ) {
		break;
	}
}

if ( empty( $attributes ) ) {
	$attributes = array();
}

$bundle_candidate_ids = array();

if ( method_exists( $product, 'get_cross_sell_ids' ) ) {
	$bundle_candidate_ids = array_merge( $bundle_candidate_ids, array_map( 'absint', $product->get_cross_sell_ids() ) );
}

if ( function_exists( 'wc_get_related_products' ) ) {
	$bundle_candidate_ids = array_merge( $bundle_candidate_ids, wc_get_related_products( $product_id, 3 ) );
}

$bundle_candidate_ids = array_values( array_unique( array_filter( array_map( 'absint', $bundle_candidate_ids ) ) ) );

foreach ( $bundle_candidate_ids as $bundle_candidate_id ) {
	if ( $bundle_candidate_id === $product_id ) {
		continue;
	}

	$bundle_product = wc_get_product( $bundle_candidate_id );

	if ( ! $bundle_product instanceof WC_Product || ! $bundle_product->is_visible() ) {
		continue;
	}

	$bundle_products[] = $bundle_product;

	if ( count( $bundle_products ) >= 2 ) {
		break;
	}
}

$bundle_items[] = array(
	'type'    => 'product',
	'name'    => $product_name,
	'product' => $product,
	'price'   => (float) wc_get_price_to_display( $product ),
	'regular' => $regular_price > 0 ? $regular_price : (float) wc_get_price_to_display( $product ),
);

foreach ( $bundle_products as $bundle_product ) {
	$bundle_product_regular = $bundle_product->is_type( 'variable' ) ? (float) $bundle_product->get_variation_regular_price( 'min', true ) : (float) $bundle_product->get_regular_price();
	$bundle_product_price   = (float) wc_get_price_to_display( $bundle_product );

	$bundle_items[] = array(
		'type'    => 'product',
		'name'    => $bundle_product->get_name(),
		'product' => $bundle_product,
		'price'   => $bundle_product_price,
		'regular' => $bundle_product_regular > 0 ? $bundle_product_regular : $bundle_product_price,
	);
}

foreach ( $bundle_items as $bundle_item ) {
	$bundle_total   += isset( $bundle_item['price'] ) ? (float) $bundle_item['price'] : 0.0;
	$bundle_regular += isset( $bundle_item['regular'] ) ? (float) $bundle_item['regular'] : 0.0;
}

$bundle_save                = max( 0, $bundle_regular - $bundle_total );
$bundle_add_product_ids     = array();
$bundle_can_add_to_cart     = count( $bundle_items ) >= 3;
$bundle_unavailable_product = false;

foreach ( $bundle_items as $bundle_item ) {
	$bundle_item_product = isset( $bundle_item['product'] ) && $bundle_item['product'] instanceof WC_Product ? $bundle_item['product'] : null;

	if ( ! $bundle_item_product instanceof WC_Product || ! $bundle_item_product->is_type( 'simple' ) || ! $bundle_item_product->is_purchasable() || ! $bundle_item_product->is_in_stock() ) {
		$bundle_unavailable_product = true;
		break;
	}

	$bundle_add_product_ids[] = (int) $bundle_item_product->get_id();
}

$bundle_add_product_ids = array_values( array_unique( array_filter( $bundle_add_product_ids ) ) );

if ( $bundle_unavailable_product || count( $bundle_add_product_ids ) !== count( $bundle_items ) ) {
	$bundle_can_add_to_cart = false;
}

if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && function_exists( 'wc_get_checkout_url' ) ) {
	$buy_now_url = add_query_arg(
		array(
			'add-to-cart' => $product_id,
			'quantity'    => 1,
		),
		wc_get_checkout_url()
	);
}
?>

<section class="pdp-main" aria-labelledby="pdp-product-title">
	<div class="container pdp-main__grid">
		<div class="pdp-main__gallery" aria-label="<?php esc_attr_e( 'Product gallery', 'genzee-jewels' ); ?>" data-pdp-gallery>
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10 (we have our own sale badge)
			 * @hooked woocommerce_show_product_images - 20 (we have our own gallery)
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>

			<?php if ( ! empty( $image_ids ) ) : ?>
				<div class="pdp-main__thumbs" aria-label="<?php esc_attr_e( 'Product images', 'genzee-jewels' ); ?>">
					<?php foreach ( array_slice( $image_ids, 0, 5 ) as $index => $image_id ) : ?>
						<?php
						$thumb_alt = sprintf(
							/* translators: 1: product name, 2: image number. */
							__( '%1$s image %2$d', 'genzee-jewels' ),
							$product_name,
							$index + 1
						);
						?>
						<button
							class="pdp-main__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
							type="button"
							aria-label="<?php echo esc_attr( $thumb_alt ); ?>"
							aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							data-pdp-gallery-thumb
							data-full="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) ); ?>"
							data-srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $image_id, 'woocommerce_single' ) ); ?>"
							data-sizes="<?php echo esc_attr( wp_get_attachment_image_sizes( $image_id, 'woocommerce_single' ) ); ?>"
							data-alt="<?php echo esc_attr( $thumb_alt ); ?>"
						>
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'woocommerce_thumbnail',
								false,
								array(
									'alt'     => esc_attr( $thumb_alt ),
									'loading' => 0 === $index ? 'eager' : 'lazy',
								)
							);
							?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="pdp-main__media">
				<div class="pdp-main__badges" aria-label="<?php esc_attr_e( 'Product badges', 'genzee-jewels' ); ?>">
					<?php if ( '' !== $pdp_badge ) : ?>
						<span class="<?php echo esc_attr( $pdp_badge_class ); ?>"><?php echo esc_html( $pdp_badge ); ?></span>
					<?php endif; ?>
					<?php if ( $is_on_sale ) : ?>
						<span class="pdp-main__badge pdp-main__badge--butter">
							<?php
							printf(
								/* translators: %d: sale discount percentage. */
								esc_html__( '%d%% OFF', 'genzee-jewels' ),
								absint( $discount_percent )
							);
							?>
						</span>
					<?php endif; ?>
				</div>
				<div class="pdp-main__viewing">
					<span class="pulse-dot" aria-hidden="true"></span>
					<?php esc_html_e( 'Available online', 'genzee-jewels' ); ?>
				</div>

				<div class="pdp-main__image" data-pdp-gallery-main>
					<?php if ( $main_image_id ) : ?>
						<?php
						echo wp_get_attachment_image(
							$main_image_id,
							'woocommerce_single',
							false,
							array(
								'alt'     => esc_attr( $product_name ),
								'loading' => 'eager',
							)
						);
						?>
					<?php else : ?>
						<div class="pdp-main__image-placeholder" aria-hidden="true">
							<span><?php esc_html_e( 'Product image coming soon', 'genzee-jewels' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<aside class="pdp-main__summary" aria-label="<?php esc_attr_e( 'Purchase information', 'genzee-jewels' ); ?>">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>

			<div class="pdp-main__breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'genzee-jewels' ); ?></a>
				<span aria-hidden="true">/</span>
				<?php if ( $primary_category instanceof WP_Term && ! is_wp_error( $category_url ) ) : ?>
					<a href="<?php echo esc_url( $category_url ); ?>"><?php echo esc_html( $category_label ); ?></a>
				<?php else : ?>
					<span><?php echo esc_html( $category_label ); ?></span>
				<?php endif; ?>
			</div>

			<p class="pdp-main__eyebrow"><?php echo esc_html( $category_label ); ?></p>
			<h1 class="pdp-main__title" id="pdp-product-title"><?php echo esc_html( $product_name ); ?></h1>

			<?php if ( $review_count > 0 ) : ?>
				<div class="pdp-main__rating">
					<?php
					get_template_part(
						'template-parts/components/stars',
						null,
						array(
							'rating' => $average_rating,
							'size'   => 'sm',
						)
					);
					?>
					<span>
						<?php
						printf(
							/* translators: 1: average rating, 2: review count. */
							esc_html__( '%1$s (%2$s reviews)', 'genzee-jewels' ),
							esc_html( number_format_i18n( $average_rating, 1 ) ),
							esc_html( number_format_i18n( $review_count ) )
						);
						?>
					</span>
				</div>
			<?php endif; ?>

			<div class="pdp-main__price-row">
				<div class="pdp-main__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				<?php if ( $is_on_sale ) : ?>
					<span class="pdp-main__discount">
						<?php
						printf(
							/* translators: %d: sale discount percentage. */
							esc_html__( '%d%% OFF', 'genzee-jewels' ),
							absint( $discount_percent )
						);
						?>
					</span>
				<?php endif; ?>
			</div>

			<p class="pdp-main__microcopy"><?php esc_html_e( 'Inclusive of all taxes. Shipping calculated at checkout.', 'genzee-jewels' ); ?></p>

			<div class="pdp-main__deal" aria-label="<?php esc_attr_e( 'Current deal', 'genzee-jewels' ); ?>">
				<div>
					<span>⚡ <?php esc_html_e( 'DEAL ENDS IN', 'genzee-jewels' ); ?></span>
					<small><?php esc_html_e( 'Price goes back up after', 'genzee-jewels' ); ?></small>
				</div>
				<div class="pdp-main__countdown" data-countdown="7200" aria-label="<?php esc_attr_e( 'Time remaining', 'genzee-jewels' ); ?>">
					<b class="js-countdown-h" aria-hidden="true">02</b>
					<b class="js-countdown-m" aria-hidden="true">00</b>
					<b class="js-countdown-s" aria-hidden="true">00</b>
				</div>
			</div>

			<?php
			$stock_classes = array( 'pdp-main__stock', 'pdp-main__stock--ok' );
			$stock_title   = __( 'In stock', 'genzee-jewels' );
			$stock_detail  = __( 'Ready to ship', 'genzee-jewels' );

			if ( $is_low_stock ) {
				$stock_classes = array( 'pdp-main__stock', 'pdp-main__stock--low' );
				$stock_title   = sprintf(
					/* translators: %s: stock quantity. */
					__( 'Only %s left', 'genzee-jewels' ),
					number_format_i18n( $stock_quantity )
				);
				$stock_detail  = __( 'Selling fast', 'genzee-jewels' );
			} elseif ( ! $product->is_in_stock() ) {
				$stock_classes = array( 'pdp-main__stock', 'pdp-main__stock--out' );
				$stock_title   = __( 'Out of stock', 'genzee-jewels' );
				$stock_detail  = __( 'This piece is currently unavailable.', 'genzee-jewels' );
			} elseif ( null !== $stock_quantity && $stock_quantity > 0 ) {
				$stock_title = sprintf(
					/* translators: %s: stock quantity. */
					__( '%s in stock', 'genzee-jewels' ),
					number_format_i18n( $stock_quantity )
				);
				$stock_detail = __( 'Ready to ship', 'genzee-jewels' );
			}
			?>
			<div class="<?php echo esc_attr( implode( ' ', $stock_classes ) ); ?>">
				<span class="pdp-main__stock-dot" aria-hidden="true"></span>
				<span class="pdp-main__stock-copy">
					<strong><?php echo esc_html( $stock_title ); ?></strong>
					<small><?php echo esc_html( $stock_detail ); ?></small>
				</span>
			</div>

			<?php foreach ( $attributes as $attribute ) : ?>
				<div class="pdp-main__options" aria-label="<?php echo esc_attr( $attribute['label'] ); ?>">
					<div class="pdp-main__options-label">
						<?php echo esc_html( $attribute['label'] ); ?>:
						<span><?php echo esc_html( $attribute['options'][0] ); ?></span>
					</div>
					<div class="pdp-main__option-list">
						<?php foreach ( $attribute['options'] as $index => $option ) : ?>
							<button
								class="pdp-main__option<?php echo 0 === $index ? ' is-active' : ''; ?>"
								type="button"
								aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
								<span class="pdp-main__swatch pdp-main__swatch--<?php echo esc_attr( sanitize_html_class( strtolower( $option ) ) ); ?>" aria-hidden="true"></span>
								<?php echo esc_html( $option ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

			<div class="pdp-main__cart">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>

			<button
				type="button"
				class="pdp-main__wishlist"
				aria-label="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Save %s to wishlist', 'genzee-jewels' ),
							$product_name
						)
					);
				?>"
				aria-pressed="false"
				data-wishlist-toggle
				data-product-id="<?php echo esc_attr( $product_id ); ?>"
				data-label-add="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Save %s to wishlist', 'genzee-jewels' ),
							$product_name
						)
					);
				?>"
				data-label-remove="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Remove %s from wishlist', 'genzee-jewels' ),
							$product_name
						)
					);
				?>">
				<svg class="pdp-main__wishlist-icon" width="18" height="18" viewBox="0 0 24 24"
					fill="none" stroke="currentColor" stroke-width="2"
					aria-hidden="true" focusable="false">
					<path d="M12 21C6 17 2 13 2 8a5 5 0 0 1 10-2 5 5 0 0 1 10 2c0 5-4 9-10 13z"/>
				</svg>
				<span data-wishlist-label><?php esc_html_e( 'Save', 'genzee-jewels' ); ?></span>
			</button>

			<a class="pdp-main__buy-now" href="<?php echo esc_url( $buy_now_url ); ?>" aria-label="<?php esc_attr_e( 'Buy it now', 'genzee-jewels' ); ?>">
				<?php esc_html_e( 'Buy it now', 'genzee-jewels' ); ?>
			</a>

			<section class="pdp-main__offers" aria-labelledby="pdp-offers-title">
				<h2 class="pdp-main__panel-title" id="pdp-offers-title">🎁 <?php esc_html_e( 'Offers you unlock', 'genzee-jewels' ); ?></h2>
				<ul class="pdp-main__offer-list">
					<li class="pdp-main__offer">
						<span class="pdp-main__offer-icon" aria-hidden="true">💸</span>
						<span class="pdp-main__offer-copy">
							<strong><?php esc_html_e( 'Extra 10% off on prepaid orders', 'genzee-jewels' ); ?></strong>
							<small><?php
							printf(
								/* translators: %s: coupon code */
								esc_html__( 'Code: %s', 'genzee-jewels' ),
								'<b>GENZ10</b>'
							);
							?></small>
						</span>
						<button type="button" class="pdp-main__offer-chip"><?php esc_html_e( 'Apply', 'genzee-jewels' ); ?></button>
					</li>
					<li class="pdp-main__offer">
						<span class="pdp-main__offer-icon" aria-hidden="true">🎁</span>
						<span class="pdp-main__offer-copy">
							<strong><?php esc_html_e( 'FREE nose pin on orders above ₹799', 'genzee-jewels' ); ?></strong>
						</span>
						<button type="button" class="pdp-main__offer-chip"><?php esc_html_e( 'Apply', 'genzee-jewels' ); ?></button>
					</li>
					<li class="pdp-main__offer">
						<span class="pdp-main__offer-icon" aria-hidden="true">💝</span>
						<span class="pdp-main__offer-copy">
							<strong><?php esc_html_e( 'Buy 2 sets, get 3rd FREE', 'genzee-jewels' ); ?></strong>
							<small><?php
							printf(
								/* translators: %s: coupon code */
								esc_html__( 'Code: %s', 'genzee-jewels' ),
								'<b>COMBO3</b>'
							);
							?></small>
						</span>
						<button type="button" class="pdp-main__offer-chip"><?php esc_html_e( 'Apply', 'genzee-jewels' ); ?></button>
					</li>
				</ul>
			</section>

			<section class="pdp-main__delivery" aria-labelledby="pdp-delivery-title">
				<div class="pdp-main__delivery-head">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M1 3h15v13H1zM16 8h4l3 4v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
					<h2 class="pdp-main__panel-title pdp-main__panel-title--dark" id="pdp-delivery-title"><?php esc_html_e( 'Delivery & Returns', 'genzee-jewels' ); ?></h2>
				</div>
				<div class="pdp-main__pincode" role="group" aria-label="<?php esc_attr_e( 'PIN code check', 'genzee-jewels' ); ?>">
					<input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="<?php esc_attr_e( 'Enter PIN code', 'genzee-jewels' ); ?>" aria-label="<?php esc_attr_e( 'Enter PIN code', 'genzee-jewels' ); ?>">
					<button type="button"><?php esc_html_e( 'Check', 'genzee-jewels' ); ?></button>
				</div>
				<ul class="pdp-main__delivery-list">
					<li><?php esc_html_e( 'Ships within 24 hrs', 'genzee-jewels' ); ?></li>
					<li><?php esc_html_e( 'COD available', 'genzee-jewels' ); ?></li>
					<li><?php esc_html_e( '7-day easy returns', 'genzee-jewels' ); ?></li>
				</ul>
			</section>
		</aside>
	</div>

	<?php if ( count( $bundle_products ) >= 2 ) : ?>
		<section
			class="pdp-bundle"
			aria-labelledby="pdp-bundle-title"
			data-pdp-bundle
			data-bundle-product-ids="<?php echo esc_attr( implode( ',', $bundle_add_product_ids ) ); ?>">
			<div class="container pdp-bundle__inner">
				<div class="pdp-bundle__head">
					<div>
						<p class="pdp-bundle__eyebrow"><?php esc_html_e( 'Complete the Look', 'genzee-jewels' ); ?></p>
						<h2 class="pdp-bundle__title" id="pdp-bundle-title"><?php esc_html_e( 'Bundle picks for this style', 'genzee-jewels' ); ?></h2>
					</div>
				</div>

				<div class="pdp-bundle__items">
					<?php foreach ( $bundle_items as $index => $bundle_item ) : ?>
						<?php
						$bundle_product = isset( $bundle_item['product'] ) && $bundle_item['product'] instanceof WC_Product ? $bundle_item['product'] : null;
						$bundle_link    = $bundle_product ? get_permalink( $bundle_product->get_id() ) : '';
						?>
						<article class="pdp-bundle__item">
							<a class="pdp-bundle__media" href="<?php echo esc_url( $bundle_link ); ?>">
								<?php if ( $bundle_product && $bundle_product->get_image_id() ) : ?>
									<?php
									echo wp_kses_post(
										$bundle_product->get_image(
											'woocommerce_thumbnail',
											array(
												'alt'     => esc_attr( $bundle_item['name'] ),
												'loading' => 'lazy',
											)
										)
									);
									?>
								<?php else : ?>
									<span class="pdp-bundle__placeholder" aria-hidden="true"></span>
								<?php endif; ?>
							</a>
							<div class="pdp-bundle__copy">
								<h3><a href="<?php echo esc_url( $bundle_link ); ?>"><?php echo esc_html( $bundle_item['name'] ); ?></a></h3>
								<p><?php echo wp_kses_post( wc_price( $bundle_item['price'] ) ); ?></p>
								<a class="pdp-bundle__link" href="<?php echo esc_url( $bundle_link ); ?>"><?php esc_html_e( 'View product', 'genzee-jewels' ); ?></a>
							</div>
						</article>
						<?php if ( $index < count( $bundle_items ) - 1 ) : ?>
							<span class="pdp-bundle__plus" aria-hidden="true">+</span>
						<?php endif; ?>
					<?php endforeach; ?>
					<span class="pdp-bundle__equals" aria-hidden="true">=</span>
					<form class="pdp-bundle__total-form" method="post" action="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
						<?php wp_nonce_field( 'genzee_add_bundle_' . $product_id, 'genzee_bundle_nonce' ); ?>
						<?php foreach ( $bundle_add_product_ids as $bundle_add_product_id ) : ?>
							<input type="hidden" name="genzee_bundle_product_ids[]" value="<?php echo esc_attr( $bundle_add_product_id ); ?>">
						<?php endforeach; ?>
						<button
							class="pdp-bundle__total"
							type="submit"
							name="genzee_add_bundle"
							value="<?php echo esc_attr( $product_id ); ?>"
							data-pdp-bundle-submit
							<?php disabled( ! $bundle_can_add_to_cart ); ?>
						>
							<span><?php echo esc_html( $bundle_can_add_to_cart ? __( 'Add bundle to cart', 'genzee-jewels' ) : __( 'Bundle unavailable', 'genzee-jewels' ) ); ?></span>
							<strong><?php echo wp_kses_post( wc_price( $bundle_total ) ); ?></strong>
							<?php if ( $bundle_save > 0 ) : ?>
								<small>
									<?php
									printf(
										/* translators: %s: formatted savings amount. */
										esc_html__( 'Save %s', 'genzee-jewels' ),
										esc_html( wp_strip_all_tags( wc_price( $bundle_save ) ) )
									);
									?>
								</small>
							<?php endif; ?>
						</button>
					</form>
				</div>
			</div>
		</section>
	<?php endif; ?>
</section>
