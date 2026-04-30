<?php
/**
 * Reusable WooCommerce-aware product card.
 *
 * USAGE — real WooCommerce product:
 *   get_template_part( 'template-parts/components/product-card', null, [
 *       'product' => $wc_product, // WC_Product instance
 *   ] );
 *
 * USAGE — demo / fallback array (WooCommerce inactive or no products yet):
 *   get_template_part( 'template-parts/components/product-card', null, [
 *       'demo' => [
 *           'id'      => 'demo-1',      // unique string ID for SVG gradient
 *           'name'    => 'Product Name',
 *           'cat'     => 'Earrings',
 *           'price'   => 299,           // current / sale price (int|float)
 *           'mrp'     => 599,           // original MRP — omit if not on sale
 *           'rating'  => 4.8,           // float 0–5 — omit to hide stars
 *           'reviews' => 1284,          // review count int — omit to hide count
 *           'stock'   => 3,             // int ≤5 triggers low-stock badge
 *           'tag'     => 'BESTSELLER',  // badge label string — omit / null for none
 *           'color'   => '#FFE14A',     // hex bg for placeholder art
 *           'link'    => '/shop/',      // product URL — defaults to /shop/
 *       ],
 *   ] );
 *
 * Layout: uses the "stretched link" pattern — the product-name <a> stretches
 * to cover the whole card via ::before; the wishlist and quick-add sit at a
 * higher z-index so they remain independently clickable.
 *
 * Sections MUST resolve product data before calling this template.
 * This card never runs its own WP_Query.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ──────────────────────────────────────────────────────────────
   1. Resolve data source
────────────────────────────────────────────────────────────── */

$args = isset( $args ) && is_array( $args ) ? $args : array();

$product_obj = null;
if (
	isset( $args['product'] )
	&& class_exists( 'WC_Product' )
	&& $args['product'] instanceof WC_Product
) {
	$product_obj = $args['product'];
}

$demo = ( isset( $args['demo'] ) && is_array( $args['demo'] ) ) ? $args['demo'] : null;

// Nothing to render.
if ( null === $product_obj && null === $demo ) {
	return;
}

/* ──────────────────────────────────────────────────────────────
   2. Normalise to flat locals via helper
────────────────────────────────────────────────────────────── */

$data = genzee_get_product_card_data( $product_obj ? $product_obj : $demo );

/* ──────────────────────────────────────────────────────────────
   3. Render
   Layout note: product-card has position:relative.
   The product name <a> stretches via ::before pseudo-element to
   cover the whole card (z-index 1). Quick-add sits at z-index 2
   to remain independently clickable above that layer.
────────────────────────────────────────────────────────────── */
?>
<article class="product-card" aria-label="<?php echo esc_attr( $data['name'] ); ?>">

	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10 (we have our own link pattern)
	 */
	?>

	<?php /* ── Image / media area ──────────────────────────────────── */ ?>
	<div class="product-card__media">

		<?php if ( $data['has_image'] ) : ?>
			<?php echo wp_kses_post( $data['image_html'] ); ?>
		<?php else : ?>
			<div class="product-card__placeholder">
				<?php
				if ( function_exists( 'genzee_render_product_art_svg' ) ) {
					echo genzee_render_product_art_svg( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>

		<?php /* Badge pill — top-left */ ?>
		<?php if ( '' !== $data['tag'] ) : ?>
			<span class="<?php echo esc_attr( $data['tag_class'] ); ?>" aria-hidden="true">
				<?php echo esc_html( $data['tag'] ); ?>
			</span>
		<?php endif; ?>

		<?php /* Discount chip — top-right */ ?>
		<?php if ( $data['discount'] > 0 ) : ?>
			<span class="product-card__discount"
				aria-label="<?php
					/* translators: %d: discount percentage number */
					printf( esc_attr__( '%d percent off', 'genzee-jewels' ), $data['discount'] );
				?>">
				<?php echo esc_html( '-' . $data['discount'] . '%' ); ?>
			</span>
		<?php endif; ?>

		<?php /* Quick-add overlay on desktop; mobile uses the fixed body CTA below. */ ?>
			<div class="product-card__quick-add-wrap">
				<a href="<?php echo esc_url( $data['quick_add_url'] ); ?>"
					class="<?php echo esc_attr( $data['quick_add_classes'] ); ?>"
					<?php if ( $data['is_wc_product'] && class_exists( 'WooCommerce' ) ) : ?>
						data-product_id="<?php echo esc_attr( $data['id'] ); ?>"
						data-product_sku="<?php echo esc_attr( $data['sku'] ); ?>"
						data-quantity="1"
						aria-label="<?php echo esc_attr( $data['atc_description'] ); ?>"
						rel="nofollow"
					<?php endif; ?>>
					<svg class="product-card__quick-add-icon" width="13" height="13" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2.5"
						aria-hidden="true" focusable="false">
						<path d="M12 5v14M5 12h14"/>
					</svg>
					<span class="product-card__quick-add-text">
						<?php echo esc_html( $data['quick_add_label'] ); ?>
					</span>
					<span class="product-card__quick-add-dot" aria-hidden="true">&middot;</span>
					<span class="product-card__quick-add-price">
						<?php echo esc_html( $data['quick_add_price'] ); ?>
					</span>
				</a>
			</div>

		<?php /* Wishlist button — z-index 2, sits above the stretched link */ ?>
		<?php if ( $data['is_wc_product'] ) : ?>
			<button
				type="button"
				class="product-card__wishlist"
				aria-label="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Save %s to wishlist', 'genzee-jewels' ),
							$data['name']
						)
					);
				?>"
				aria-pressed="false"
				data-wishlist-toggle
				data-product-id="<?php echo esc_attr( $data['id'] ); ?>"
				data-label-add="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Save %s to wishlist', 'genzee-jewels' ),
							$data['name']
						)
					);
				?>"
				data-label-remove="<?php
					echo esc_attr(
						sprintf(
							/* translators: %s: product name */
							__( 'Remove %s from wishlist', 'genzee-jewels' ),
							$data['name']
						)
					);
				?>">
				<svg class="product-card__wishlist-icon" width="14" height="14" viewBox="0 0 24 24"
					fill="none" stroke="currentColor" stroke-width="2"
					aria-hidden="true" focusable="false">
					<path d="M12 21C6 17 2 13 2 8a5 5 0 0 1 10-2 5 5 0 0 1 10 2c0 5-4 9-10 13z"/>
				</svg>
			</button>
		<?php endif; ?>

	</div><!-- /.product-card__media -->

	<?php /* ── Text body ──────────────────────────────────────────── */ ?>
	<div class="product-card__body">

		<?php if ( '' !== $data['cat_name'] ) : ?>
			<span class="product-card__cat"><?php echo esc_html( $data['cat_name'] ); ?></span>
		<?php endif; ?>

		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		?>

		<h3 class="product-card__name">
			<?php
			/**
			 * Hook: woocommerce_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			?>

			<?php /* The ::before pseudo-element on this link stretches to cover the whole card */ ?>
			<a href="<?php echo esc_url( $data['permalink'] ); ?>" class="product-card__stretched-link">
				<?php echo esc_html( $data['name'] ); ?>
			</a>
		</h3>

		<?php
		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		?>

		<?php if ( $data['has_rating'] ) : ?>
			<div class="product-card__meta">
				<?php
				get_template_part( 'template-parts/components/stars', null, array(
					'rating' => $data['rating'],
					'size'   => 'sm',
				) );
				?>
				<span class="product-card__reviews">
					(<?php echo esc_html( number_format_i18n( $data['review_count'] ) ); ?>)
				</span>
			</div>
		<?php endif; ?>

		<div class="product-card__purchase-block">
			<?php
			get_template_part( 'template-parts/components/price', null, array(
				'price'   => $data['sale_str'],
				'regular' => $data['regular_str'],
				'off'     => $data['discount'],
				'size'    => 'sm',
			) );
			?>

			<?php if ( $data['low_stock'] && $data['stock_qty'] > 0 ) : ?>
				<div class="product-card__status-row">
					<p class="product-card__low-stock">
						<span class="pulse-dot product-card__low-stock-dot" aria-hidden="true"></span>
						<?php
						/* translators: %d: number of items remaining in stock */
						printf( esc_html__( 'Only %d left!', 'genzee-jewels' ), $data['stock_qty'] );
						?>
					</p>
				</div>
			<?php endif; ?>

			<a
				href="<?php echo esc_url( $data['quick_add_url'] ); ?>"
				class="<?php echo esc_attr( $data['mobile_cta_classes'] ); ?>"
				<?php if ( $data['is_wc_product'] && class_exists( 'WooCommerce' ) ) : ?>
					data-product_id="<?php echo esc_attr( $data['id'] ); ?>"
					data-product_sku="<?php echo esc_attr( $data['sku'] ); ?>"
					data-quantity="1"
					aria-label="<?php echo esc_attr( $data['atc_description'] ); ?>"
					rel="nofollow"
				<?php endif; ?>>
				<?php echo esc_html( $data['mobile_cta_label'] ); ?>
			</a>
		</div>

	</div><!-- /.product-card__body -->

	<?php
	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	?>

</article>
