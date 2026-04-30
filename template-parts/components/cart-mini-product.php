<?php
/**
 * Mini product tile for cart-drawer impulse cross-sells.
 *
 * Accepts either:
 * - product: WC_Product instance.
 * - demo: fallback array with id, name, price, mrp, color, link.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

if ( ! $product_obj && ! $demo ) {
	return;
}

$name        = '';
$permalink   = home_url( '/shop/' );
$action_url  = $permalink;
$price_html  = '';
$regular_raw = 0.0;
$price_raw   = 0.0;
$discount    = 0;
$image_html  = '';
$has_image   = false;
$color       = '#FFE14A';
$slug_id     = 'demo';
$is_wc_tile  = false;
$product_id  = 0;
$is_simple_ajax_add = false;

if ( $product_obj ) {
	$is_wc_tile = true;
	$product_id = (int) $product_obj->get_id();
	$slug_id    = 'wc-' . $product_id;
	$name       = $product_obj->get_name();
	$permalink  = get_permalink( $product_id );
	$color      = array( '#FFE14A', '#C9B6FF', '#FF7A3D', '#B8F2C9', '#FF2D6F', '#F4EFE6' )[ $product_id % 6 ];

	if ( $product_obj->is_type( 'variable' ) ) {
		$price_raw   = (float) $product_obj->get_variation_price( 'min', true );
		$regular_raw = (float) $product_obj->get_variation_regular_price( 'min', true );
	} else {
		$regular_base = (float) $product_obj->get_regular_price();
		$price_raw    = function_exists( 'wc_get_price_to_display' )
			? (float) wc_get_price_to_display( $product_obj )
			: (float) $product_obj->get_price();
		$regular_raw  = $regular_base > 0 && function_exists( 'wc_get_price_to_display' )
			? (float) wc_get_price_to_display( $product_obj, array( 'price' => $regular_base ) )
			: $regular_base;
	}

	if ( $price_raw > 0 && function_exists( 'wc_price' ) ) {
		$price_html = wc_price( $price_raw );
	} else {
		$price_html = $product_obj->get_price_html();
	}

	if ( $product_obj->is_on_sale() && $regular_raw > $price_raw && $price_raw > 0 ) {
		$discount = (int) round( ( ( $regular_raw - $price_raw ) / $regular_raw ) * 100 );
	}

	$has_image = (int) $product_obj->get_image_id() > 0;
	if ( $has_image ) {
		$image_html = $product_obj->get_image(
			'woocommerce_thumbnail',
			array(
				'class'   => 'cart-mini-product__img',
				'loading' => 'lazy',
				'alt'     => esc_attr( $name ),
			)
		);
	}

	$action_url = ( $product_obj->is_purchasable() && $product_obj->is_in_stock() && $product_obj->is_type( 'simple' ) )
		? $product_obj->add_to_cart_url()
		: $permalink;
	$is_simple_ajax_add = $product_obj->is_type( 'simple' ) && $product_obj->is_purchasable() && $product_obj->is_in_stock();
} else {
	$name        = isset( $demo['name'] ) ? (string) $demo['name'] : __( 'Mini pick', 'genzee-jewels' );
	$permalink   = isset( $demo['link'] ) ? (string) $demo['link'] : home_url( '/shop/' );
	$action_url  = $permalink;
	$price_raw   = isset( $demo['price'] ) ? (float) $demo['price'] : 0.0;
	$regular_raw = isset( $demo['mrp'] ) ? (float) $demo['mrp'] : 0.0;
	$price_html  = function_exists( 'wc_price' ) ? wc_price( $price_raw ) : 'Rs ' . number_format( $price_raw, 0 );
	$discount    = ( $regular_raw > $price_raw && $price_raw > 0 )
		? (int) round( ( ( $regular_raw - $price_raw ) / $regular_raw ) * 100 )
		: 0;
	$color       = isset( $demo['color'] ) ? (string) $demo['color'] : $color;
	$slug_id     = isset( $demo['id'] ) ? sanitize_html_class( (string) $demo['id'] ) : 'demo';
}
?>
<article class="cart-mini-product" aria-label="<?php echo esc_attr( $name ); ?>">
	<a class="cart-mini-product__link" href="<?php echo esc_url( $permalink ); ?>">
		<span class="cart-mini-product__media">
			<?php if ( $has_image ) : ?>
				<?php echo wp_kses_post( $image_html ); ?>
			<?php else : ?>
				<svg class="cart-mini-product__placeholder" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
					<rect width="80" height="80" fill="<?php echo esc_attr( $color ); ?>"/>
					<defs>
						<radialGradient id="cart-mini-<?php echo esc_attr( $slug_id ); ?>" cx="50%" cy="38%" r="68%">
							<stop offset="0%" stop-color="rgba(255,255,255,0.5)"/>
							<stop offset="100%" stop-color="rgba(0,0,0,0.08)"/>
						</radialGradient>
					</defs>
					<rect width="80" height="80" fill="url(#cart-mini-<?php echo esc_attr( $slug_id ); ?>)"/>
					<circle cx="29" cy="40" r="13" fill="none" stroke="rgba(255,255,255,0.78)" stroke-width="4"/>
					<circle cx="51" cy="40" r="13" fill="none" stroke="rgba(255,255,255,0.78)" stroke-width="4"/>
					<circle cx="20" cy="31" r="2" fill="rgba(255,255,255,0.76)"/>
				</svg>
			<?php endif; ?>
			<?php if ( $discount > 0 ) : ?>
				<span class="cart-mini-product__badge">
					-<?php echo esc_html( $discount ); ?>%
				</span>
			<?php endif; ?>
		</span>
		<span class="cart-mini-product__name"><?php echo esc_html( $name ); ?></span>
		<span class="cart-mini-product__price"><?php echo wp_kses_post( $price_html ); ?></span>
	</a>
	<a
		class="cart-mini-product__add<?php echo $is_simple_ajax_add ? ' add_to_cart_button ajax_add_to_cart' : ''; ?>"
		href="<?php echo esc_url( $action_url ); ?>"
		aria-label="<?php echo esc_attr( sprintf( __( 'Add or view %s', 'genzee-jewels' ), $name ) ); ?>"
		<?php if ( $is_wc_tile && $product_id > 0 ) : ?>
			data-product_id="<?php echo esc_attr( $product_id ); ?>"
			data-product_sku="<?php echo esc_attr( $product_obj->get_sku() ); ?>"
			data-quantity="1"
			rel="nofollow"
		<?php endif; ?>
	>
		<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" aria-hidden="true" focusable="false">
			<path d="M12 5v14M5 12h14"/>
		</svg>
	</a>
</article>
