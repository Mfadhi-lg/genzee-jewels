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
   2. Colour palette for placeholder art (cycles by product ID)
────────────────────────────────────────────────────────────── */

$palette = array( '#FFE14A', '#C9B6FF', '#FF7A3D', '#B8F2C9', '#FF2D6F', '#F4EFE6' );

/* ──────────────────────────────────────────────────────────────
   3. Normalise to flat locals
────────────────────────────────────────────────────────────── */

if ( $product_obj ) {

	// Identification.
	$prod_id  = (int) $product_obj->get_id();
	$slug_id  = 'p' . $prod_id;
	$name     = $product_obj->get_name();
	$permalink = get_permalink( $prod_id );

	// Category — first assigned term.
	$terms    = get_the_terms( $prod_id, 'product_cat' );
	$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

	// Pricing.
	$regular_raw = (float) $product_obj->get_regular_price();
	$sale_raw    = (float) $product_obj->get_sale_price();
	$on_sale     = $product_obj->is_on_sale() && $sale_raw > 0 && $regular_raw > $sale_raw;
	$display_raw = $on_sale ? $sale_raw : $regular_raw;

	$sale_str    = '₹' . number_format( $display_raw, 0, '.', ',' );
	$regular_str = $on_sale ? '₹' . number_format( $regular_raw, 0, '.', ',' ) : '';
	$discount    = ( $on_sale && $regular_raw > 0 )
		? (int) round( ( ( $regular_raw - $sale_raw ) / $regular_raw ) * 100 )
		: 0;

	// Rating.
	$rating       = (float) $product_obj->get_average_rating();
	$review_count = (int) $product_obj->get_rating_count();
	$has_rating   = $review_count > 0;

	// Stock.
	$manages_stock = $product_obj->managing_stock();
	$stock_qty     = (int) $product_obj->get_stock_quantity();
	$low_stock     = $manages_stock && $stock_qty > 0 && $stock_qty <= 5;

	// Badge — custom meta 'genzee_badge' wins; auto-fallbacks: low-stock, sale.
	$tag = (string) $product_obj->get_meta( 'genzee_badge', true );
	if ( '' === $tag ) {
		if ( $low_stock ) {
			$tag = 'LOW STOCK';
		} elseif ( $on_sale ) {
			$tag = 'SALE';
		}
	}

	// Image.
	$image_id   = (int) $product_obj->get_image_id();
	$has_image  = $image_id > 0;
	$image_html = $has_image
		? $product_obj->get_image(
			'woocommerce_thumbnail',
			array(
				'loading' => 'lazy',
				'class'   => 'product-card__img',
				'alt'     => esc_attr( $name ),
			)
		)
		: '';

	// Placeholder colour.
	$color = $palette[ $prod_id % count( $palette ) ];

	// Add-to-cart URL (WC built-in, handles simple + variable).
	$atc_url = $product_obj->add_to_cart_url();

} else {

	// Demo array — all values sanitised below before output.
	$prod_id   = isset( $demo['id'] ) ? $demo['id'] : 'demo';
	$slug_id   = 'demo-' . $prod_id;
	$name      = isset( $demo['name'] )  ? (string) $demo['name']  : '';
	$permalink = isset( $demo['link'] )  ? (string) $demo['link']  : home_url( '/shop/' );
	$cat_name  = isset( $demo['cat'] )   ? (string) $demo['cat']   : '';

	$price_raw   = isset( $demo['price'] ) ? (float) $demo['price'] : 0.0;
	$mrp_raw     = isset( $demo['mrp'] )   ? (float) $demo['mrp']   : 0.0;
	$on_sale     = $mrp_raw > $price_raw && $price_raw > 0;
	$sale_str    = '₹' . number_format( $price_raw, 0, '.', ',' );
	$regular_str = $on_sale ? '₹' . number_format( $mrp_raw, 0, '.', ',' ) : '';
	$discount    = ( $on_sale && $mrp_raw > 0 )
		? (int) round( ( ( $mrp_raw - $price_raw ) / $mrp_raw ) * 100 )
		: 0;

	$rating       = isset( $demo['rating'] )  ? (float) $demo['rating']  : 0.0;
	$review_count = isset( $demo['reviews'] ) ? (int)   $demo['reviews'] : 0;
	$has_rating   = $review_count > 0 || $rating > 0;

	$stock_qty = isset( $demo['stock'] ) ? (int) $demo['stock'] : 999;
	$low_stock = $stock_qty > 0 && $stock_qty <= 5;

	$tag   = isset( $demo['tag'] ) ? (string) $demo['tag'] : '';
	$color = ( isset( $demo['color'] ) && '' !== $demo['color'] )
		? (string) $demo['color']
		: $palette[0];

	$has_image  = false;
	$image_html = '';
	$atc_url    = home_url( '/shop/' );
}

/* ──────────────────────────────────────────────────────────────
   4. Badge colour class
────────────────────────────────────────────────────────────── */

$tag_class = 'product-card__tag';
if ( '' !== $tag ) {
	$tag_upper = strtoupper( $tag );
	if ( 'LOW STOCK' === $tag_upper || false !== strpos( $tag_upper, 'TRENDING' ) ) {
		$tag_class .= ' product-card__tag--hot';
	} elseif ( 'BESTSELLER' === $tag_upper || 'SET' === $tag_upper || 'COMBO DEAL' === $tag_upper ) {
		$tag_class .= ' product-card__tag--butter';
	} else {
		$tag_class .= ' product-card__tag--ink';
	}
}

/* ──────────────────────────────────────────────────────────────
   5. Render
   Layout note: product-card has position:relative.
   The product name <a> stretches via ::before pseudo-element to
   cover the whole card (z-index 1). Wishlist + quick-add sit at
   z-index 2 to remain independently clickable above that layer.
────────────────────────────────────────────────────────────── */
?>
<article class="product-card" aria-label="<?php echo esc_attr( $name ); ?>">

	<?php /* ── Image / media area ──────────────────────────────────── */ ?>
	<div class="product-card__media">

		<?php if ( $has_image ) : ?>
			<?php echo wp_kses_post( $image_html ); ?>
		<?php else : ?>
			<div class="product-card__placeholder">
				<svg class="product-card__placeholder-svg"
					viewBox="0 0 280 280"
					preserveAspectRatio="xMidYMid slice"
					focusable="false"
					xmlns="http://www.w3.org/2000/svg">
					<rect width="280" height="280" fill="<?php echo esc_attr( $color ); ?>"/>
					<defs>
						<radialGradient id="pgv-<?php echo esc_attr( $slug_id ); ?>" cx="50%" cy="38%" r="68%">
							<stop offset="0%" stop-color="rgba(255,255,255,0.45)"/>
							<stop offset="100%" stop-color="rgba(0,0,0,0.06)"/>
						</radialGradient>
					</defs>
					<rect width="280" height="280" fill="url(#pgv-<?php echo esc_attr( $slug_id ); ?>)"/>
					<?php /* Generic hoop-earring silhouette */ ?>
					<g transform="translate(80,140)">
						<circle cx="0" cy="0" r="30" fill="none" stroke="rgba(255,255,255,0.72)" stroke-width="7"/>
						<circle cx="-20" cy="-20" r="4" fill="rgba(255,255,255,0.65)"/>
					</g>
					<g transform="translate(200,140)">
						<circle cx="0" cy="0" r="30" fill="none" stroke="rgba(255,255,255,0.72)" stroke-width="7"/>
						<circle cx="-20" cy="-20" r="4" fill="rgba(255,255,255,0.65)"/>
					</g>
				</svg>
			</div>
		<?php endif; ?>

		<?php /* Badge pill — top-left */ ?>
		<?php if ( '' !== $tag ) : ?>
			<span class="<?php echo esc_attr( $tag_class ); ?>" aria-hidden="true">
				<?php echo esc_html( $tag ); ?>
			</span>
		<?php endif; ?>

		<?php /* Discount chip — top-right */ ?>
		<?php if ( $discount > 0 ) : ?>
			<span class="product-card__discount"
				aria-label="<?php
					/* translators: %d: discount percentage number */
					printf( esc_attr__( '%d percent off', 'genzee-jewels' ), $discount );
				?>">
				<?php echo esc_html( '-' . $discount . '%' ); ?>
			</span>
		<?php endif; ?>

		<?php /* Quick-add — WooCommerce real product only; z-index 2 overrides stretched link */ ?>
		<?php if ( $product_obj && class_exists( 'WooCommerce' ) ) : ?>
			<div class="product-card__quick-add-wrap" aria-hidden="true">
				<a href="<?php echo esc_url( $atc_url ); ?>"
					class="product-card__quick-add"
					data-product-id="<?php echo esc_attr( $prod_id ); ?>"
					rel="nofollow"
					tabindex="-1">
					<svg width="13" height="13" viewBox="0 0 24 24"
						fill="none" stroke="currentColor" stroke-width="2.5"
						aria-hidden="true" focusable="false">
						<path d="M12 5v14M5 12h14"/>
					</svg>
					<?php esc_html_e( 'Quick Add', 'genzee-jewels' ); ?>
					&nbsp;&middot;&nbsp;<?php echo esc_html( $sale_str ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php /* Wishlist button — z-index 2, sits above the stretched link */ ?>
		<button class="product-card__wishlist"
			type="button"
			aria-label="<?php
				/* translators: %s: product name */
				printf( esc_attr__( 'Add %s to wishlist', 'genzee-jewels' ), esc_attr( $name ) );
			?>">
			<svg width="14" height="14" viewBox="0 0 24 24"
				fill="none" stroke="currentColor" stroke-width="2"
				aria-hidden="true" focusable="false">
				<path d="M12 21C6 17 2 13 2 8a5 5 0 0 1 10-2 5 5 0 0 1 10 2c0 5-4 9-10 13z"/>
			</svg>
		</button>

	</div><!-- /.product-card__media -->

	<?php /* ── Text body ──────────────────────────────────────────── */ ?>
	<div class="product-card__body">

		<?php if ( '' !== $cat_name ) : ?>
			<span class="product-card__cat"><?php echo esc_html( $cat_name ); ?></span>
		<?php endif; ?>

		<h3 class="product-card__name">
			<?php /* The ::before pseudo-element on this link stretches to cover the whole card */ ?>
			<a href="<?php echo esc_url( $permalink ); ?>" class="product-card__stretched-link">
				<?php echo esc_html( $name ); ?>
			</a>
		</h3>

		<?php if ( $has_rating ) : ?>
			<div class="product-card__meta">
				<?php
				get_template_part( 'template-parts/components/stars', null, array(
					'rating' => $rating,
					'size'   => 'sm',
				) );
				?>
				<span class="product-card__reviews">
					(<?php echo esc_html( number_format_i18n( $review_count ) ); ?>)
				</span>
			</div>
		<?php endif; ?>

		<?php
		get_template_part( 'template-parts/components/price', null, array(
			'price'   => $sale_str,
			'regular' => $regular_str,
			'off'     => $discount,
			'size'    => 'sm',
		) );
		?>

		<?php if ( $low_stock && $stock_qty > 0 ) : ?>
			<p class="product-card__low-stock">
				<span class="pulse-dot product-card__low-stock-dot" aria-hidden="true"></span>
				<?php
				/* translators: %d: number of items remaining in stock */
				printf( esc_html__( 'Only %d left!', 'genzee-jewels' ), $stock_qty );
				?>
			</p>
		<?php endif; ?>

	</div><!-- /.product-card__body -->

</article>
