<?php
/**
 * Helper functions for product-related logic.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalizes product data for use in product cards.
 *
 * Supports both WC_Product objects and demo arrays.
 *
 * @param WC_Product|array $product_or_demo The product object or demo data array.
 * @return array Normalized product data.
 */
function genzee_get_product_card_data( $product_or_demo ) {
	$is_wc_active = class_exists( 'WooCommerce' ) && class_exists( 'WC_Product' );

	if ( $is_wc_active && $product_or_demo instanceof WC_Product ) {
		$product_obj = $product_or_demo;
		$prod_id     = (int) $product_obj->get_id();
		$slug_id     = 'p' . $prod_id;
		$name        = $product_obj->get_name();
		$permalink   = get_permalink( $prod_id );

		// Category — first assigned term.
		$terms    = get_the_terms( $prod_id, 'product_cat' );
		$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

		// Pricing.
		$is_variable = $product_obj->is_type( 'variable' );

		if ( $is_variable ) {
			$display_raw     = (float) $product_obj->get_variation_price( 'min', true );
			$display_max_raw = (float) $product_obj->get_variation_price( 'max', true );
			$regular_raw     = (float) $product_obj->get_variation_regular_price( 'min', true );
			$sale_raw        = (float) $product_obj->get_variation_sale_price( 'min', true );
			$price_varies    = $display_raw > 0 && $display_max_raw > $display_raw;
		} else {
			$base_regular_raw = (float) $product_obj->get_regular_price();
			$base_sale_raw    = (float) $product_obj->get_sale_price();
			$regular_raw      = $base_regular_raw > 0 && function_exists( 'wc_get_price_to_display' )
				? (float) wc_get_price_to_display( $product_obj, array( 'price' => $base_regular_raw ) )
				: $base_regular_raw;
			$sale_raw         = $base_sale_raw > 0 && function_exists( 'wc_get_price_to_display' )
				? (float) wc_get_price_to_display( $product_obj, array( 'price' => $base_sale_raw ) )
				: $base_sale_raw;
			$display_raw      = function_exists( 'wc_get_price_to_display' )
				? (float) wc_get_price_to_display( $product_obj )
				: (float) $product_obj->get_price();
			$price_varies     = false;
		}

		$on_sale = $product_obj->is_on_sale() && $sale_raw > 0 && $regular_raw > $sale_raw;

		$sale_str = $display_raw > 0 && function_exists( 'wc_price' )
			? wc_price( $display_raw )
			: $product_obj->get_price_html();

		if ( $price_varies && '' !== $sale_str ) {
			$sale_str = sprintf(
				/* translators: %s: minimum product price */
				esc_html__( 'From %s', 'genzee-jewels' ),
				$sale_str
			);
		}

		$regular_str = $on_sale && function_exists( 'wc_price' ) ? wc_price( $regular_raw ) : '';
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
			$tag_terms = get_the_terms( $prod_id, 'product_tag' );
			if ( $tag_terms && ! is_wp_error( $tag_terms ) ) {
				foreach ( $tag_terms as $tag_term ) {
					$tag_slug = sanitize_title( $tag_term->slug );
					if ( 'bestseller' === $tag_slug ) {
						$tag = 'BESTSELLER';
						break;
					}
					if ( 'trending' === $tag_slug ) {
						$tag = 'TRENDING';
						break;
					}
					if ( 'new' === $tag_slug ) {
						$tag = 'NEW';
						break;
					}
					if ( 'set' === $tag_slug ) {
						$tag = 'SET';
						break;
					}
					if ( 'combo' === $tag_slug || 'combo-deal' === $tag_slug ) {
						$tag = 'COMBO DEAL';
						break;
					}
				}
			}

			if ( '' === $tag && $low_stock ) {
				$tag = 'LOW STOCK';
			} elseif ( '' === $tag && $on_sale ) {
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

		$art_traits = genzee_get_product_art_traits( $name, $cat_name, $tag, $prod_id );
		$color      = $art_traits['color'];
		$shape      = $art_traits['shape'];
		$tone       = $art_traits['tone'];

		$is_variable     = $product_obj->is_type( 'variable' );
		$is_simple       = $product_obj->is_type( 'simple' );
		$is_purchasable  = $product_obj->is_purchasable();
		$is_in_stock     = $product_obj->is_in_stock();
		$sku             = $product_obj->get_sku();
		$atc_description = $product_obj->add_to_cart_description();
		$is_wc_product   = true;
		$atc_url         = $is_simple && $is_purchasable && $is_in_stock
			? $product_obj->add_to_cart_url()
			: $permalink;

	} else {
		// Demo array or fallback.
		$demo      = is_array( $product_or_demo ) ? $product_or_demo : array();
		$prod_id   = isset( $demo['id'] ) ? $demo['id'] : 'demo';
		$slug_id   = 'demo-' . $prod_id;
		$name      = isset( $demo['name'] )  ? (string) $demo['name']  : '';
		$permalink = isset( $demo['link'] )  ? (string) $demo['link']  : home_url( '/shop/' );
		$cat_name  = isset( $demo['cat'] )   ? (string) $demo['cat']   : '';

		$price_raw   = isset( $demo['price'] ) ? (float) $demo['price'] : 0.0;
		$mrp_raw     = isset( $demo['mrp'] )   ? (float) $demo['mrp']   : 0.0;
		$on_sale     = $mrp_raw > $price_raw && $price_raw > 0;
		$discount    = ( $on_sale && $mrp_raw > 0 )
			? (int) round( ( ( $mrp_raw - $price_raw ) / $mrp_raw ) * 100 )
			: 0;
		$sale_str    = function_exists( 'wc_price' ) ? wc_price( $price_raw ) : 'Rs ' . number_format( $price_raw, 0, '.', ',' );
		$regular_str = $on_sale ? ( function_exists( 'wc_price' ) ? wc_price( $mrp_raw ) : 'Rs ' . number_format( $mrp_raw, 0, '.', ',' ) ) : '';

		$rating       = isset( $demo['rating'] )  ? (float) $demo['rating']  : 0.0;
		$review_count = isset( $demo['reviews'] ) ? (int)   $demo['reviews'] : 0;
		$has_rating   = $review_count > 0 || $rating > 0;

		$stock_qty = isset( $demo['stock'] ) ? (int) $demo['stock'] : 999;
		$low_stock = $stock_qty > 0 && $stock_qty <= 5;

		$tag   = isset( $demo['tag'] ) ? (string) $demo['tag'] : '';
		$art_traits = genzee_get_product_art_traits( $name, $cat_name, $tag, is_numeric( $prod_id ) ? (int) $prod_id : 0 );
		$color = ( isset( $demo['color'] ) && '' !== $demo['color'] )
			? (string) $demo['color']
			: $art_traits['color'];
		$shape = ( isset( $demo['shape'] ) && '' !== $demo['shape'] )
			? (string) $demo['shape']
			: $art_traits['shape'];
		$tone = ( isset( $demo['tone'] ) && '' !== $demo['tone'] )
			? (string) $demo['tone']
			: $art_traits['tone'];

		$has_image       = false;
		$image_html      = '';
		$atc_url         = home_url( '/shop/' );
		$is_variable     = false;
		$is_simple       = false;
		$is_purchasable  = false;
		$is_in_stock     = false;
		$sku             = '';
		$atc_description = '';
		$is_wc_product   = false;
	}

	$quick_add_url   = $atc_url;
	$quick_add_label = ! $is_simple
		? esc_html__( 'Select options', 'genzee-jewels' )
		: esc_html__( 'Quick Add', 'genzee-jewels' );

	$mobile_cta_label = ! $is_simple
		? esc_html__( 'Select options', 'genzee-jewels' )
		: esc_html__( 'Add to cart', 'genzee-jewels' );

	$quick_add_price = trim( wp_strip_all_tags( $sale_str ) );

	$quick_add_classes  = array( 'product-card__quick-add' );
	$mobile_cta_classes = array( 'product-card__mobile-cta' );

	$use_wc_ajax_add_to_cart = ( $is_simple && $is_purchasable && $is_in_stock );

	if ( $use_wc_ajax_add_to_cart ) {
		$quick_add_classes[]  = 'button';
		$mobile_cta_classes[] = 'button';
		$quick_add_classes[]  = 'add_to_cart_button';
		$mobile_cta_classes[] = 'add_to_cart_button';
		$quick_add_classes[]  = 'ajax_add_to_cart';
		$mobile_cta_classes[] = 'ajax_add_to_cart';
	}

	$tag_class = genzee_get_product_badge_class( $tag );

	return array(
		'id'                      => $prod_id,
		'slug_id'                 => $slug_id,
		'name'                    => $name,
		'permalink'               => $permalink,
		'cat_name'                => $cat_name,
		'sale_str'                => $sale_str,
		'regular_str'             => $regular_str,
		'discount'                => $discount,
		'on_sale'                 => $on_sale,
		'rating'                  => $rating,
		'review_count'            => $review_count,
		'has_rating'              => $has_rating,
		'stock_qty'               => $stock_qty,
		'low_stock'               => $low_stock,
		'tag'                     => $tag,
		'tag_class'               => $tag_class,
		'color'                   => $color,
		'shape'                   => $shape,
		'tone'                    => $tone,
		'has_image'               => $has_image,
		'image_html'              => $image_html,
		'quick_add_url'           => $quick_add_url,
		'quick_add_label'         => $quick_add_label,
		'quick_add_price'         => $quick_add_price,
		'quick_add_classes'       => implode( ' ', $quick_add_classes ),
		'mobile_cta_label'        => $mobile_cta_label,
		'mobile_cta_classes'      => implode( ' ', $mobile_cta_classes ),
		'use_wc_ajax_add_to_cart' => $use_wc_ajax_add_to_cart,
		'sku'                     => $sku,
		'atc_description'         => $atc_description,
		'is_wc_product'           => $is_wc_product,
	);
}

/**
 * Infers design-file product-art traits from product text.
 *
 * @param string     $name Product name.
 * @param string     $category Product category label.
 * @param string     $tag Product badge/tag.
 * @param int|string $seed Stable seed for fallback colour.
 * @return array{color:string,shape:string,tone:string}
 */
function genzee_get_product_art_traits( $name, $category = '', $tag = '', $seed = 0 ) {
	$palette = array( '#FFE14A', '#FAF7F1', '#FF2D6F', '#C9C9D6', '#C9B6FF', '#F4EFE6', '#FF7A3D', '#B8F2C9', '#3B82F6' );
	$text    = strtolower( $name . ' ' . $category . ' ' . $tag );
	$index   = is_numeric( $seed ) ? absint( $seed ) : absint( crc32( (string) $seed ) );

	$traits = array(
		'color' => $palette[ $index % count( $palette ) ],
		'shape' => 'hoop',
		'tone'  => false !== strpos( $text, 'silver' ) || false !== strpos( $text, 'chrome' ) ? 'silver' : 'gold',
	);

	$rules = array(
		array( array( 'heart', 'huggie', 'butter' ), 'heart', '#FFE14A', 'gold' ),
		array( array( 'pearl', 'drop' ), 'drop', '#FAF7F1', 'silver' ),
		array( array( 'cherry' ), 'cherry', '#FF2D6F', 'gold' ),
		array( array( 'hoop', 'chrome' ), 'hoop', '#C9C9D6', 'silver' ),
		array( array( 'choker', 'starburst' ), 'star', '#C9B6FF', 'gold' ),
		array( array( 'chain', 'layered' ), 'chain', '#FFE14A', 'gold' ),
		array( array( 'lariat' ), 'lariat', '#F4EFE6', 'silver' ),
		array( array( 'ring set', 'bubble ring' ), 'rings', '#FF7A3D', 'gold' ),
		array( array( 'evil eye', 'signet' ), 'signet', '#3B82F6', 'silver' ),
		array( array( 'daisy' ), 'daisy', '#B8F2C9', 'gold' ),
		array( array( 'paperclip' ), 'paperclip', '#FFE14A', 'gold' ),
		array( array( 'anklet' ), 'anklet', '#C9B6FF', 'silver' ),
		array( array( 'tennis' ), 'tennis', '#F4EFE6', 'silver' ),
		array( array( 'nose', 'pin' ), 'nose', '#FAF7F1', 'gold' ),
		array( array( 'moon', 'stars', 'combo', 'party combo' ), 'moonset', '#0A0A0A', 'gold' ),
		array( array( 'ribbon', 'coquette' ), 'ribbonset', '#FF2D6F', 'gold' ),
	);

	foreach ( $rules as $rule ) {
		foreach ( $rule[0] as $needle ) {
			if ( false !== strpos( $text, $needle ) ) {
				$traits['shape'] = $rule[1];
				$traits['color'] = $rule[2];
				$traits['tone']  = $rule[3];
				return $traits;
			}
		}
	}

	return $traits;
}

/**
 * Renders the design-file inspired SVG product art used when no image exists.
 *
 * @param array $data Normalized product-card data.
 * @return string SVG markup.
 */
function genzee_render_product_art_svg( $data ) {
	$shape      = isset( $data['shape'] ) ? sanitize_key( $data['shape'] ) : 'hoop';
	$color      = isset( $data['color'] ) ? sanitize_hex_color( $data['color'] ) : '#FFE14A';
	$tone       = isset( $data['tone'] ) && 'silver' === $data['tone'] ? 'silver' : 'gold';
	$metal      = 'gold' === $tone ? '#E6B84D' : '#C8CCD4';
	$metal_deep = 'gold' === $tone ? '#B8872C' : '#8F94A0';
	$slug_id    = isset( $data['slug_id'] ) ? sanitize_html_class( $data['slug_id'] ) : 'product';

	if ( ! $color ) {
		$color = '#FFE14A';
	}

	ob_start();
	?>
	<svg class="product-card__placeholder-svg"
		viewBox="0 0 280 280"
		preserveAspectRatio="xMidYMid slice"
		focusable="false"
		xmlns="http://www.w3.org/2000/svg">
		<rect width="280" height="280" fill="<?php echo esc_attr( $color ); ?>"/>
		<defs>
			<radialGradient id="pgv-<?php echo esc_attr( $slug_id ); ?>" cx="50%" cy="38%" r="68%">
				<stop offset="0%" stop-color="rgba(255,255,255,0.45)"/>
				<stop offset="100%" stop-color="rgba(0,0,0,0.08)"/>
			</radialGradient>
		</defs>
		<rect width="280" height="280" fill="url(#pgv-<?php echo esc_attr( $slug_id ); ?>)"/>
		<?php switch ( $shape ) :
			case 'heart' :
				?>
				<g>
					<path d="M105 125c-15-25-44-9-5 26 39-35 10-51-5-26z" fill="<?php echo esc_attr( $metal ); ?>" stroke="<?php echo esc_attr( $metal_deep ); ?>" stroke-width="2"/>
					<path d="M175 125c-15-25-44-9-5 26 39-35 10-51-5-26z" fill="<?php echo esc_attr( $metal ); ?>" stroke="<?php echo esc_attr( $metal_deep ); ?>" stroke-width="2"/>
					<circle cx="96" cy="113" r="4" fill="rgba(255,255,255,0.7)"/>
					<circle cx="166" cy="113" r="4" fill="rgba(255,255,255,0.7)"/>
				</g>
				<?php
				break;
			case 'drop' :
				?>
				<g fill="none" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2">
					<circle cx="110" cy="110" r="4" fill="<?php echo esc_attr( $metal ); ?>"/>
					<line x1="110" y1="114" x2="110" y2="140"/>
					<ellipse cx="110" cy="160" rx="14" ry="18" fill="#F8F3E6" stroke="<?php echo esc_attr( $metal_deep ); ?>"/>
					<circle cx="170" cy="110" r="4" fill="<?php echo esc_attr( $metal ); ?>"/>
					<line x1="170" y1="114" x2="170" y2="140"/>
					<ellipse cx="170" cy="160" rx="14" ry="18" fill="#F8F3E6" stroke="<?php echo esc_attr( $metal_deep ); ?>"/>
				</g>
				<?php
				break;
			case 'cherry' :
				?>
				<g transform="translate(140 140)">
					<path d="M-30-40Q-10-60 0-40" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2.5" fill="none"/>
					<path d="M30-40Q10-60 0-40" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2.5" fill="none"/>
					<circle cx="-30" cy="-10" r="22" fill="#E11855"/>
					<circle cx="30" cy="-10" r="22" fill="#E11855"/>
					<circle cx="-36" cy="-16" r="6" fill="rgba(255,255,255,0.4)"/>
					<circle cx="24" cy="-16" r="6" fill="rgba(255,255,255,0.4)"/>
					<path d="M-15-45Q-5-50 5-45Q0-35-10-38Z" fill="#0E8A5F"/>
				</g>
				<?php
				break;
			case 'star' :
				?>
				<g>
					<path d="M40 170Q140 140 240 170" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2" fill="none"/>
					<path d="M70 155l4 10h10l-8 6 3 10-9-6-9 6 3-10-8-6h10z" fill="<?php echo esc_attr( $metal ); ?>"/>
					<path d="M140 150l4 10h10l-8 6 3 10-9-6-9 6 3-10-8-6h10z" fill="<?php echo esc_attr( $metal ); ?>"/>
					<path d="M210 155l4 10h10l-8 6 3 10-9-6-9 6 3-10-8-6h10z" fill="<?php echo esc_attr( $metal ); ?>"/>
				</g>
				<?php
				break;
			case 'chain' :
				?>
				<g fill="<?php echo esc_attr( $metal ); ?>">
					<path d="M30 130Q140 100 250 130v5Q140 105 30 135Z"/>
					<path d="M50 165Q140 150 230 165v4Q140 154 50 169Z" opacity="0.85"/>
					<circle cx="140" cy="175" r="8"/>
					<path d="M140 175v20M135 190h10" stroke="<?php echo esc_attr( $metal_deep ); ?>" stroke-width="2"/>
				</g>
				<?php
				break;
			case 'lariat' :
				?>
				<g fill="none">
					<path d="M50 110Q140 160 230 110" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2"/>
					<circle cx="140" cy="155" r="6" fill="#FAF7F1" stroke="<?php echo esc_attr( $metal_deep ); ?>"/>
					<line x1="140" y1="161" x2="140" y2="185" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="1.5"/>
					<circle cx="140" cy="192" r="8" fill="#FAF7F1" stroke="<?php echo esc_attr( $metal_deep ); ?>"/>
				</g>
				<?php
				break;
			case 'rings' :
				?>
				<g fill="none" stroke-width="10">
					<circle cx="85" cy="140" r="35" stroke="<?php echo esc_attr( $metal ); ?>"/>
					<circle cx="140" cy="140" r="35" stroke="#FF2D6F"/>
					<circle cx="195" cy="140" r="35" stroke="<?php echo esc_attr( $metal ); ?>"/>
				</g>
				<?php
				break;
			case 'signet' :
				?>
				<g transform="translate(140 140)">
					<circle cx="0" cy="0" r="50" fill="none" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="8"/>
					<ellipse cx="0" cy="-30" rx="30" ry="22" fill="<?php echo esc_attr( $metal ); ?>"/>
					<ellipse cx="0" cy="-30" rx="22" ry="15" fill="#3B82F6"/>
					<circle cx="0" cy="-30" r="7" fill="#0A0A0A"/>
					<circle cx="0" cy="-30" r="3" fill="#FFF"/>
				</g>
				<?php
				break;
			case 'daisy' :
				?>
				<g transform="translate(140 140)">
					<circle cx="0" cy="0" r="50" fill="none" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="6"/>
					<ellipse cx="0" cy="-44" rx="5" ry="9" fill="#FFF"/>
					<ellipse cx="10" cy="-40" rx="5" ry="9" fill="#FFF" transform="rotate(45 10 -40)"/>
					<ellipse cx="14" cy="-30" rx="5" ry="9" fill="#FFF" transform="rotate(90 14 -30)"/>
					<ellipse cx="10" cy="-20" rx="5" ry="9" fill="#FFF" transform="rotate(135 10 -20)"/>
					<ellipse cx="0" cy="-16" rx="5" ry="9" fill="#FFF"/>
					<ellipse cx="-10" cy="-20" rx="5" ry="9" fill="#FFF" transform="rotate(45 -10 -20)"/>
					<ellipse cx="-14" cy="-30" rx="5" ry="9" fill="#FFF" transform="rotate(90 -14 -30)"/>
					<ellipse cx="-10" cy="-40" rx="5" ry="9" fill="#FFF" transform="rotate(135 -10 -40)"/>
					<circle cx="0" cy="-30" r="6" fill="#FFE14A"/>
				</g>
				<?php
				break;
			case 'anklet' :
				?>
				<g fill="none">
					<path d="M50 140Q140 110 230 140" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="3" stroke-dasharray="1 4"/>
					<circle cx="90" cy="132" r="5" fill="#FF2D6F" stroke="none"/>
					<path d="M140 120l3 8h8l-7 5 3 8-7-5-7 5 3-8-7-5h8z" fill="<?php echo esc_attr( $metal ); ?>" stroke="none"/>
					<circle cx="190" cy="132" r="5" fill="#C9B6FF" stroke="none"/>
				</g>
				<?php
				break;
			case 'tennis' :
				?>
				<g>
					<path d="M30 140h220" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2"/>
					<?php for ( $i = 0; $i < 11; $i++ ) : ?>
						<rect x="<?php echo esc_attr( (string) ( 34 + ( $i * 20 ) ) ); ?>" y="134" width="12" height="12" fill="#FFF" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="1" transform="rotate(45 <?php echo esc_attr( (string) ( 40 + ( $i * 20 ) ) ); ?> 140)"/>
					<?php endfor; ?>
				</g>
				<?php
				break;
			case 'paperclip' :
				?>
				<g fill="none" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="4">
					<rect x="48" y="130" width="34" height="14" rx="7"/>
					<rect x="78" y="130" width="42" height="14" rx="7"/>
					<rect x="116" y="130" width="34" height="14" rx="7"/>
					<rect x="146" y="130" width="42" height="14" rx="7"/>
					<rect x="184" y="130" width="34" height="14" rx="7"/>
				</g>
				<?php
				break;
			case 'nose' :
				?>
				<g transform="translate(140 140)" fill="none" stroke="<?php echo esc_attr( $metal ); ?>">
					<circle cx="0" cy="0" r="4" fill="#FFF"/>
					<path d="M0 0Q-30 10-25 40Q-20 55 0 55Q20 55 25 40Q30 10 0 0" stroke-width="1.5"/>
					<circle cx="0" cy="0" r="2" fill="#FF2D6F" stroke="none"/>
				</g>
				<?php
				break;
			case 'moonset' :
				?>
				<g fill="<?php echo esc_attr( $metal ); ?>">
					<path d="M100 90a20 20 0 1 1 0 40a15 15 0 0 0 0-40Z"/>
					<path d="M180 72l5 16h16l-13 9 5 16-13-10-13 10 5-16-13-9h16z"/>
					<path d="M90 185l4 10h10l-8 7 3 10-9-6-9 6 3-10-8-7h10z"/>
					<circle cx="200" cy="200" r="14" fill="#FFE14A" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2"/>
				</g>
				<?php
				break;
			case 'ribbonset' :
				?>
				<g transform="translate(140 140)" fill="#FFF" stroke="<?php echo esc_attr( $metal ); ?>" stroke-width="2">
					<path d="M-50-10Q-70-30-40-20Q-30-10 0 0Q30-10 40-20Q70-30 50-10Q30 10 0 10Q-30 10-50-10Z"/>
					<path d="M-10 0h20l5 25h-30Z"/>
					<circle cx="0" cy="0" r="6" fill="<?php echo esc_attr( $metal ); ?>"/>
				</g>
				<?php
				break;
			case 'hoop' :
			default :
				?>
				<g fill="none" stroke="<?php echo esc_attr( $metal ); ?>">
					<circle cx="110" cy="140" r="32" stroke-width="7"/>
					<circle cx="170" cy="140" r="32" stroke-width="7"/>
					<circle cx="88" cy="118" r="4" fill="rgba(255,255,255,0.7)" stroke="none"/>
					<circle cx="148" cy="118" r="4" fill="rgba(255,255,255,0.7)" stroke="none"/>
				</g>
				<?php
				break;
		endswitch;
		?>
	</svg>
	<?php

	return (string) ob_get_clean();
}

/**
 * Returns the CSS class for a product badge based on its label.
 *
 * @param string $tag The badge label.
 * @return string CSS class string.
 */
function genzee_get_product_badge_class( $tag ) {
	$tag_class = 'product-card__tag';
	if ( '' === $tag ) {
		return $tag_class;
	}

	$tag_upper = strtoupper( $tag );
	if ( 'LOW STOCK' === $tag_upper || false !== strpos( $tag_upper, 'TRENDING' ) ) {
		$tag_class .= ' product-card__tag--hot';
	} elseif ( 'BESTSELLER' === $tag_upper || 'SET' === $tag_upper || 'COMBO DEAL' === $tag_upper ) {
		$tag_class .= ' product-card__tag--butter';
	} else {
		$tag_class .= ' product-card__tag--ink';
	}

	return $tag_class;
}
