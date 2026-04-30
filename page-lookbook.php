<?php
/**
 * Template Name: Lookbook
 *
 * Shoppable editorial lookbook page based on design_files/other-pages.jsx.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'genzee_lookbook_demo_products' ) ) {
	/**
	 * Demo products mirror the lookbook mock when matching WooCommerce products are absent.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	function genzee_lookbook_demo_products() {
		$cat_url = static function ( $slug ) {
			return home_url( '/product-category/' . sanitize_title( $slug ) . '/' );
		};

		return array(
			'butter-heart-huggies' => array(
				'id'      => 'lb-butter-heart',
				'sku'     => 'GJ-EAR-001',
				'name'    => 'Butter Heart Huggies',
				'cat'     => 'Earrings',
				'price'   => 299,
				'mrp'     => 599,
				'rating'  => 4.8,
				'reviews' => 1284,
				'stock'   => 3,
				'tag'     => 'BESTSELLER',
				'color'   => '#FFE14A',
				'shape'   => 'heart',
				'tone'    => 'gold',
				'link'    => $cat_url( 'earrings' ),
			),
			'pixie-pearl-drops'    => array(
				'id'      => 'lb-pixie-pearl',
				'name'    => 'Pixie Pearl Drops',
				'cat'     => 'Earrings',
				'price'   => 249,
				'mrp'     => 499,
				'rating'  => 4.7,
				'reviews' => 892,
				'stock'   => 12,
				'tag'     => 'NEW',
				'color'   => '#FAF7F1',
				'shape'   => 'drop',
				'tone'    => 'silver',
				'link'    => $cat_url( 'earrings' ),
			),
			'cherry-bomb-studs'    => array(
				'id'      => 'lb-cherry-bomb',
				'sku'     => 'GJ-EAR-002',
				'name'    => 'Cherry Bomb Studs',
				'cat'     => 'Earrings',
				'price'   => 199,
				'mrp'     => 399,
				'rating'  => 4.9,
				'reviews' => 2103,
				'stock'   => 5,
				'tag'     => 'TRENDING',
				'color'   => '#FF2D6F',
				'shape'   => 'cherry',
				'tone'    => 'gold',
				'link'    => $cat_url( 'earrings' ),
			),
			'y2k-chrome-hoops'     => array(
				'id'      => 'lb-y2k-hoops',
				'name'    => 'Y2K Chrome Hoops',
				'cat'     => 'Earrings',
				'price'   => 349,
				'mrp'     => 699,
				'rating'  => 4.6,
				'reviews' => 567,
				'stock'   => 8,
				'tag'     => '',
				'color'   => '#C9C9D6',
				'shape'   => 'hoop',
				'tone'    => 'silver',
				'link'    => $cat_url( 'earrings' ),
			),
			'starburst-choker'     => array(
				'id'      => 'lb-starburst',
				'name'    => 'Starburst Choker',
				'cat'     => 'Necklaces',
				'price'   => 449,
				'mrp'     => 899,
				'rating'  => 4.8,
				'reviews' => 734,
				'stock'   => 2,
				'tag'     => 'LOW STOCK',
				'color'   => '#C9B6FF',
				'shape'   => 'star',
				'tone'    => 'gold',
				'link'    => $cat_url( 'necklaces' ),
			),
			'layered-dainty-chain' => array(
				'id'      => 'lb-layered-chain',
				'sku'     => 'GJ-NEC-001',
				'name'    => 'Layered Dainty Chain',
				'cat'     => 'Necklaces',
				'price'   => 399,
				'mrp'     => 799,
				'rating'  => 4.9,
				'reviews' => 1456,
				'stock'   => 15,
				'tag'     => 'BESTSELLER',
				'color'   => '#FFE14A',
				'shape'   => 'chain',
				'tone'    => 'gold',
				'link'    => $cat_url( 'necklaces' ),
			),
			'pearl-baby-lariat'    => array(
				'id'      => 'lb-pearl-lariat',
				'sku'     => 'GJ-NEC-002',
				'name'    => 'Pearl Baby Lariat',
				'cat'     => 'Necklaces',
				'price'   => 299,
				'mrp'     => 599,
				'rating'  => 4.7,
				'reviews' => 523,
				'stock'   => 9,
				'tag'     => '',
				'color'   => '#F4EFE6',
				'shape'   => 'lariat',
				'tone'    => 'silver',
				'link'    => $cat_url( 'necklaces' ),
			),
			'evil-eye-signet'      => array(
				'id'      => 'lb-evil-eye',
				'name'    => 'Evil Eye Signet',
				'cat'     => 'Rings',
				'price'   => 199,
				'mrp'     => 399,
				'rating'  => 4.9,
				'reviews' => 876,
				'stock'   => 4,
				'tag'     => '',
				'color'   => '#3B82F6',
				'shape'   => 'signet',
				'tone'    => 'silver',
				'link'    => $cat_url( 'rings' ),
			),
			'tiny-diamond-nose-pin' => array(
				'id'      => 'lb-nose-pin',
				'sku'     => 'GJ-NOS-001',
				'name'    => 'Tiny Diamond Nose Pin',
				'cat'     => 'Nose Pins',
				'price'   => 149,
				'mrp'     => 299,
				'rating'  => 4.8,
				'reviews' => 2340,
				'stock'   => 25,
				'tag'     => 'BESTSELLER',
				'color'   => '#FAF7F1',
				'shape'   => 'nose',
				'tone'    => 'gold',
				'link'    => $cat_url( 'nose-pins' ),
			),
			'sparkle-tennis-bracelet' => array(
				'id'      => 'lb-tennis',
				'name'    => 'Sparkle Tennis Bracelet',
				'cat'     => 'Bracelets',
				'price'   => 449,
				'mrp'     => 999,
				'rating'  => 4.9,
				'reviews' => 1034,
				'stock'   => 3,
				'tag'     => 'LOW STOCK',
				'color'   => '#F4EFE6',
				'shape'   => 'tennis',
				'tone'    => 'silver',
				'link'    => $cat_url( 'bracelets' ),
			),
			'coquette-ribbon-set'  => array(
				'id'      => 'lb-ribbon-set',
				'name'    => 'Coquette Ribbon Set',
				'cat'     => 'Sets',
				'price'   => 479,
				'mrp'     => 999,
				'rating'  => 4.8,
				'reviews' => 567,
				'stock'   => 8,
				'tag'     => 'SET',
				'color'   => '#FF2D6F',
				'shape'   => 'ribbonset',
				'tone'    => 'gold',
				'link'    => $cat_url( 'sets' ),
			),
		);
	}
}

if ( ! function_exists( 'genzee_lookbook_get_wc_product' ) ) {
	/**
	 * Resolve a WooCommerce product by demo SKU.
	 *
	 * @param array<string,mixed> $demo Demo product data.
	 * @return WC_Product|null
	 */
	function genzee_lookbook_get_wc_product( $demo ) {
		if (
			empty( $demo['sku'] )
			|| ! class_exists( 'WooCommerce' )
			|| ! function_exists( 'wc_get_product_id_by_sku' )
			|| ! function_exists( 'wc_get_product' )
		) {
			return null;
		}

		$product_id = wc_get_product_id_by_sku( (string) $demo['sku'] );
		$product    = $product_id ? wc_get_product( $product_id ) : null;

		if ( ! $product instanceof WC_Product || 'publish' !== get_post_status( $product->get_id() ) ) {
			return null;
		}

		return $product;
	}
}

if ( ! function_exists( 'genzee_lookbook_price_raw' ) ) {
	/**
	 * Product current display price as a float.
	 *
	 * @param WC_Product $product Product object.
	 * @return float
	 */
	function genzee_lookbook_price_raw( $product ) {
		if ( $product->is_type( 'variable' ) ) {
			return (float) $product->get_variation_price( 'min', true );
		}

		return function_exists( 'wc_get_price_to_display' )
			? (float) wc_get_price_to_display( $product )
			: (float) $product->get_price();
	}
}

if ( ! function_exists( 'genzee_lookbook_regular_raw' ) ) {
	/**
	 * Product regular display price as a float.
	 *
	 * @param WC_Product $product Product object.
	 * @return float
	 */
	function genzee_lookbook_regular_raw( $product ) {
		if ( $product->is_type( 'variable' ) ) {
			$regular = (float) $product->get_variation_regular_price( 'min', true );
			return $regular > 0 ? $regular : genzee_lookbook_price_raw( $product );
		}

		$regular = (float) $product->get_regular_price();

		if ( $regular <= 0 ) {
			return genzee_lookbook_price_raw( $product );
		}

		return function_exists( 'wc_get_price_to_display' )
			? (float) wc_get_price_to_display( $product, array( 'price' => $regular ) )
			: $regular;
	}
}

if ( ! function_exists( 'genzee_lookbook_money' ) ) {
	/**
	 * Format a price for output.
	 *
	 * @param float $amount Price amount.
	 * @return string
	 */
	function genzee_lookbook_money( $amount ) {
		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $amount );
		}

		return '&#8377;' . number_format_i18n( $amount, 0 );
	}
}

if ( ! function_exists( 'genzee_lookbook_money_plain' ) ) {
	/**
	 * Format a price for plain text labels.
	 *
	 * @param float $amount Price amount.
	 * @return string
	 */
	function genzee_lookbook_money_plain( $amount ) {
		if ( function_exists( 'wc_price' ) ) {
			return html_entity_decode( wp_strip_all_tags( wc_price( $amount ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		}

		return 'Rs ' . number_format_i18n( $amount, 0 );
	}
}

if ( ! function_exists( 'genzee_lookbook_resolve_item' ) ) {
	/**
	 * Build a normalized lookbook item from a demo product key.
	 *
	 * @param string                    $key Product key.
	 * @param array<string,array>       $demo_products Demo products.
	 * @return array<string,mixed>|null
	 */
	function genzee_lookbook_resolve_item( $key, $demo_products ) {
		if ( empty( $demo_products[ $key ] ) ) {
			return null;
		}

		$demo    = $demo_products[ $key ];
		$product = genzee_lookbook_get_wc_product( $demo );
		$source  = $product instanceof WC_Product ? $product : $demo;
		$data    = function_exists( 'genzee_get_product_card_data' ) ? genzee_get_product_card_data( $source ) : array();

		if ( empty( $data ) ) {
			return null;
		}

		if ( $product instanceof WC_Product ) {
			$price_raw   = genzee_lookbook_price_raw( $product );
			$regular_raw = genzee_lookbook_regular_raw( $product );
			$can_add     = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
			$product_id  = (int) $product->get_id();
		} else {
			$price_raw   = isset( $demo['price'] ) ? (float) $demo['price'] : 0.0;
			$regular_raw = isset( $demo['mrp'] ) ? (float) $demo['mrp'] : $price_raw;
			$can_add     = false;
			$product_id  = 0;
		}

		return array(
			'demo'        => $demo,
			'product'     => $product,
			'data'        => $data,
			'price_raw'   => $price_raw,
			'regular_raw' => $regular_raw > 0 ? $regular_raw : $price_raw,
			'can_add'     => $can_add,
			'product_id'  => $product_id,
		);
	}
}

if ( ! function_exists( 'genzee_lookbook_product_thumb' ) ) {
	/**
	 * Render the small lookbook product thumbnail.
	 *
	 * @param array<string,mixed> $item Normalized item.
	 * @return void
	 */
	function genzee_lookbook_product_thumb( $item ) {
		$product = isset( $item['product'] ) ? $item['product'] : null;
		$data    = isset( $item['data'] ) ? $item['data'] : array();

		if ( $product instanceof WC_Product && $product->get_image_id() ) {
			echo wp_kses_post(
				$product->get_image(
					'woocommerce_thumbnail',
					array(
						'class'   => 'lookbook-product__img',
						'loading' => 'lazy',
						'alt'     => isset( $data['name'] ) ? esc_attr( $data['name'] ) : '',
					)
				)
			);
			return;
		}

		if ( function_exists( 'genzee_render_product_art_svg' ) ) {
			echo genzee_render_product_art_svg( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'genzee_lookbook_add_button' ) ) {
	/**
	 * Render row add/select CTA.
	 *
	 * @param array<string,mixed> $item Normalized item.
	 * @return void
	 */
	function genzee_lookbook_add_button( $item ) {
		$data = isset( $item['data'] ) ? $item['data'] : array();

		if ( ! empty( $item['can_add'] ) ) :
			?>
			<a
				href="<?php echo esc_url( $data['quick_add_url'] ); ?>"
				class="lookbook-product__add button add_to_cart_button ajax_add_to_cart"
				data-product_id="<?php echo esc_attr( $item['product_id'] ); ?>"
				data-product_sku="<?php echo esc_attr( $data['sku'] ); ?>"
				data-quantity="1"
				aria-label="<?php echo esc_attr( $data['atc_description'] ); ?>"
				rel="nofollow">
				<?php esc_html_e( '+ Add', 'genzee-jewels' ); ?>
			</a>
			<?php
			return;
		endif;
		?>
		<a class="lookbook-product__add lookbook-product__add--select" href="<?php echo esc_url( $data['permalink'] ); ?>">
			<?php esc_html_e( 'Shop', 'genzee-jewels' ); ?>
		</a>
		<?php
	}
}

if ( ! function_exists( 'genzee_lookbook_lifestyle_svg' ) ) {
	/**
	 * Render a design-file inspired lifestyle illustration.
	 *
	 * @param int    $variant Look number.
	 * @param string $tone Background tone.
	 * @return void
	 */
	function genzee_lookbook_lifestyle_svg( $variant, $tone ) {
		$palettes = array(
			1 => array( 'sky' => '#FFDCE6', 'skin' => '#E8B499', 'hair' => '#2A1810', 'shirt' => '#FF2D6F' ),
			2 => array( 'sky' => '#F4EFE6', 'skin' => '#D49B7A', 'hair' => '#0A0A0A', 'shirt' => '#FFFFFF' ),
			3 => array( 'sky' => '#C9B6FF', 'skin' => '#E8B499', 'hair' => '#4A2818', 'shirt' => '#FFFFFF' ),
			4 => array( 'sky' => '#0A0A0A', 'skin' => '#F0C8A8', 'hair' => '#1A0F08', 'shirt' => '#C9B6FF' ),
		);

		$palette = isset( $palettes[ $variant ] ) ? $palettes[ $variant ] : $palettes[1];
		$gid     = 'lookbook-life-' . absint( $variant ) . '-' . sanitize_html_class( $tone );
		?>
		<svg class="lookbook-life-art" viewBox="0 0 600 800" preserveAspectRatio="xMidYMid slice" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Editorial jewellery look %d', 'genzee-jewels' ), $variant ) ); ?>" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<linearGradient id="<?php echo esc_attr( $gid ); ?>" x1="0" y1="0" x2="0" y2="1">
					<stop offset="0%" stop-color="rgba(255,255,255,0.26)"/>
					<stop offset="100%" stop-color="rgba(0,0,0,0.18)"/>
				</linearGradient>
			</defs>
			<rect width="600" height="800" fill="<?php echo esc_attr( $palette['sky'] ); ?>"/>
			<rect width="600" height="800" fill="url(#<?php echo esc_attr( $gid ); ?>)"/>
			<circle cx="110" cy="118" r="72" fill="rgba(255,255,255,0.14)"/>
			<circle cx="505" cy="220" r="54" fill="rgba(255,225,74,0.2)"/>
			<g>
				<ellipse cx="300" cy="320" rx="150" ry="180" fill="<?php echo esc_attr( $palette['hair'] ); ?>"/>
				<rect x="260" y="380" width="80" height="104" rx="18" fill="<?php echo esc_attr( $palette['skin'] ); ?>"/>
				<path d="M98 484C190 454 410 454 502 484V800H98Z" fill="<?php echo esc_attr( $palette['shirt'] ); ?>"/>
				<ellipse cx="300" cy="340" rx="110" ry="140" fill="<?php echo esc_attr( $palette['skin'] ); ?>"/>
				<path d="M190 250C250 190 292 202 300 216C308 202 350 190 410 250C420 300 400 282 380 256C348 254 318 240 300 262C282 240 252 254 220 256C200 282 180 300 190 250Z" fill="<?php echo esc_attr( $palette['hair'] ); ?>"/>
				<circle cx="205" cy="370" r="8" fill="#E6B84D"/>
				<circle cx="395" cy="370" r="8" fill="#E6B84D"/>
				<path d="M220 500C260 532 340 532 380 500" stroke="#E6B84D" stroke-width="4" fill="none" stroke-linecap="round"/>
				<circle cx="300" cy="535" r="7" fill="#FF2D6F"/>
				<circle cx="260" cy="332" r="5" fill="#1A0F08"/>
				<circle cx="340" cy="332" r="5" fill="#1A0F08"/>
				<path d="M270 392C288 406 312 406 330 392" fill="none" stroke="#8F4E3C" stroke-width="4" stroke-linecap="round"/>
			</g>
		</svg>
		<?php
	}
}

$lookbook_demos = genzee_lookbook_demo_products();
$lookbook_specs = array(
	array(
		'id'       => 1,
		'title'    => __( 'Coquette Core', 'genzee-jewels' ),
		'subtitle' => __( 'Ribbons, pearls, main-character shine.', 'genzee-jewels' ),
		'keys'     => array( 'pixie-pearl-drops', 'coquette-ribbon-set', 'pearl-baby-lariat' ),
		'tone'     => 'rose',
	),
	array(
		'id'       => 2,
		'title'    => __( 'Clean Girl Edit', 'genzee-jewels' ),
		'subtitle' => __( 'Dainty gold, soft pearls, quietly expensive energy.', 'genzee-jewels' ),
		'keys'     => array( 'layered-dainty-chain', 'butter-heart-huggies', 'tiny-diamond-nose-pin' ),
		'tone'     => 'cream',
		'reverse'  => true,
	),
	array(
		'id'       => 3,
		'title'    => __( 'Y2K Revival', 'genzee-jewels' ),
		'subtitle' => __( 'Chrome on chrome with a tiny rebel ring.', 'genzee-jewels' ),
		'keys'     => array( 'y2k-chrome-hoops', 'pearl-baby-lariat', 'evil-eye-signet' ),
		'tone'     => 'lilac',
	),
	array(
		'id'       => 4,
		'title'    => __( 'Concert Ready', 'genzee-jewels' ),
		'subtitle' => __( 'Stars, studs, and sparkle that survives the encore.', 'genzee-jewels' ),
		'keys'     => array( 'starburst-choker', 'cherry-bomb-studs', 'sparkle-tennis-bracelet' ),
		'tone'     => 'noir',
		'reverse'  => true,
	),
);

$lookbook_looks = array();
foreach ( $lookbook_specs as $lookbook_spec ) {
	$items = array();

	foreach ( $lookbook_spec['keys'] as $product_key ) {
		$item = genzee_lookbook_resolve_item( $product_key, $lookbook_demos );
		if ( $item ) {
			$items[] = $item;
		}
	}

	if ( ! empty( $items ) ) {
		$lookbook_spec['items'] = $items;
		$lookbook_looks[]       = $lookbook_spec;
	}
}

$lookbook_shop_url = class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );

get_header();
?>

<div class="lookbook-page">
	<section class="lookbook-hero" aria-labelledby="lookbook-page-title">
		<div class="container lookbook-hero__inner">
			<p class="lookbook-hero__eyebrow"><?php esc_html_e( "THE LOOKBOOK - SUMMER '26", 'genzee-jewels' ); ?></p>
			<h1 class="lookbook-hero__title" id="lookbook-page-title">
				<?php esc_html_e( 'Shop the', 'genzee-jewels' ); ?>
				<em><?php esc_html_e( ' vibe.', 'genzee-jewels' ); ?></em>
			</h1>
			<p class="lookbook-hero__copy">
				<?php esc_html_e( 'Four moods, four mini-capsules, and one-tap styling for the days when choosing accessories is the whole outfit.', 'genzee-jewels' ); ?>
			</p>
			<nav class="lookbook-hero__nav" aria-label="<?php esc_attr_e( 'Jump to looks', 'genzee-jewels' ); ?>">
				<?php foreach ( $lookbook_looks as $lookbook_look ) : ?>
					<a href="#look-<?php echo esc_attr( $lookbook_look['id'] ); ?>">
						<?php echo esc_html( sprintf( __( 'Look %02d', 'genzee-jewels' ), $lookbook_look['id'] ) ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
	</section>

	<section class="lookbook-list" aria-label="<?php esc_attr_e( 'Shoppable editorial looks', 'genzee-jewels' ); ?>">
		<div class="container lookbook-list__inner">
			<?php foreach ( $lookbook_looks as $lookbook_index => $lookbook_look ) : ?>
				<?php
				$total_raw    = 0.0;
				$regular_raw  = 0.0;
				$product_ids  = array();
				$bundle_ready = true;

				foreach ( $lookbook_look['items'] as $bundle_item ) {
					$total_raw   += (float) $bundle_item['price_raw'];
					$regular_raw += (float) $bundle_item['regular_raw'];

					if ( ! empty( $bundle_item['can_add'] ) && ! empty( $bundle_item['product_id'] ) ) {
						$product_ids[] = (int) $bundle_item['product_id'];
					} else {
						$bundle_ready = false;
					}
				}

				$bundle_ids       = implode( ',', array_map( 'absint', $product_ids ) );
				$source_product_id = ! empty( $product_ids[0] ) ? (int) $product_ids[0] : 0;
				$look_classes     = array( 'lookbook-look', 'lookbook-look--' . sanitize_html_class( $lookbook_look['tone'] ) );
				if ( ! empty( $lookbook_look['reverse'] ) ) {
					$look_classes[] = 'lookbook-look--reverse';
				}
				?>
				<article
					id="look-<?php echo esc_attr( $lookbook_look['id'] ); ?>"
					class="<?php echo esc_attr( implode( ' ', $look_classes ) ); ?>"
					aria-labelledby="look-<?php echo esc_attr( $lookbook_look['id'] ); ?>-title">

					<div class="lookbook-look__media">
						<?php genzee_lookbook_lifestyle_svg( (int) $lookbook_look['id'], (string) $lookbook_look['tone'] ); ?>
						<div class="lookbook-look__shade" aria-hidden="true"></div>
						<ol class="lookbook-hotspots" aria-label="<?php echo esc_attr( sprintf( __( 'Products in %s', 'genzee-jewels' ), $lookbook_look['title'] ) ); ?>">
							<?php foreach ( $lookbook_look['items'] as $item_index => $lookbook_item ) : ?>
								<?php
								$hotspot_positions = array(
									array( 'top' => '25%', 'left' => '38%' ),
									array( 'top' => '50%', 'left' => '58%' ),
									array( 'top' => '70%', 'left' => '40%' ),
								);
								$pos = isset( $hotspot_positions[ $item_index ] ) ? $hotspot_positions[ $item_index ] : $hotspot_positions[0];
								?>
								<li style="<?php echo esc_attr( '--hotspot-top:' . $pos['top'] . ';--hotspot-left:' . $pos['left'] . ';' ); ?>">
									<a href="#look-<?php echo esc_attr( $lookbook_look['id'] ); ?>-item-<?php echo esc_attr( $item_index + 1 ); ?>">
										<span class="screen-reader-text">
											<?php echo esc_html( sprintf( __( 'View item %d, %s', 'genzee-jewels' ), $item_index + 1, $lookbook_item['data']['name'] ) ); ?>
										</span>
										<span aria-hidden="true"><?php echo esc_html( $item_index + 1 ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ol>
						<div class="lookbook-look__label">
							<?php echo esc_html( sprintf( __( 'LOOK %02d', 'genzee-jewels' ), $lookbook_look['id'] ) ); ?>
						</div>
					</div>

					<div
						class="lookbook-look__content"
						data-pdp-bundle
						<?php if ( $bundle_ready && $bundle_ids ) : ?>
							data-bundle-product-ids="<?php echo esc_attr( $bundle_ids ); ?>"
						<?php endif; ?>>
						<p class="lookbook-look__eyebrow">
							<?php echo esc_html( sprintf( __( 'LOOK %02d', 'genzee-jewels' ), $lookbook_look['id'] ) ); ?>
						</p>
						<h2 class="lookbook-look__title" id="look-<?php echo esc_attr( $lookbook_look['id'] ); ?>-title">
							<?php echo esc_html( $lookbook_look['title'] ); ?>
						</h2>
						<p class="lookbook-look__subtitle"><?php echo esc_html( $lookbook_look['subtitle'] ); ?></p>

						<ol class="lookbook-products" aria-label="<?php echo esc_attr( sprintf( __( '%s products', 'genzee-jewels' ), $lookbook_look['title'] ) ); ?>">
							<?php foreach ( $lookbook_look['items'] as $item_index => $lookbook_item ) : ?>
								<?php $item_data = $lookbook_item['data']; ?>
								<li class="lookbook-product" id="look-<?php echo esc_attr( $lookbook_look['id'] ); ?>-item-<?php echo esc_attr( $item_index + 1 ); ?>">
									<span class="lookbook-product__num" aria-hidden="true"><?php echo esc_html( $item_index + 1 ); ?></span>
									<a class="lookbook-product__media" href="<?php echo esc_url( $item_data['permalink'] ); ?>" aria-label="<?php echo esc_attr( $item_data['name'] ); ?>">
										<?php genzee_lookbook_product_thumb( $lookbook_item ); ?>
									</a>
									<div class="lookbook-product__body">
										<a class="lookbook-product__name" href="<?php echo esc_url( $item_data['permalink'] ); ?>">
											<?php echo esc_html( $item_data['name'] ); ?>
										</a>
										<div class="lookbook-product__price">
											<span><?php echo wp_kses_post( $item_data['sale_str'] ); ?></span>
											<?php if ( ! empty( $item_data['regular_str'] ) ) : ?>
												<s><?php echo wp_kses_post( $item_data['regular_str'] ); ?></s>
											<?php endif; ?>
										</div>
									</div>
									<?php genzee_lookbook_add_button( $lookbook_item ); ?>
								</li>
							<?php endforeach; ?>
						</ol>

						<div class="lookbook-bundle">
							<div class="lookbook-bundle__copy">
								<p class="lookbook-bundle__eyebrow"><?php esc_html_e( 'Shop the whole look', 'genzee-jewels' ); ?></p>
								<div class="lookbook-bundle__price">
									<span><?php echo wp_kses_post( genzee_lookbook_money( $total_raw ) ); ?></span>
									<?php if ( $regular_raw > $total_raw ) : ?>
										<s><?php echo wp_kses_post( genzee_lookbook_money( $regular_raw ) ); ?></s>
										<em><?php echo esc_html( sprintf( __( 'Save %s', 'genzee-jewels' ), genzee_lookbook_money_plain( $regular_raw - $total_raw ) ) ); ?></em>
									<?php endif; ?>
								</div>
							</div>

							<?php if ( $bundle_ready && $bundle_ids && $source_product_id ) : ?>
								<form class="lookbook-bundle__form pdp-bundle__total-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>">
									<input type="hidden" name="genzee_add_bundle" value="<?php echo esc_attr( $source_product_id ); ?>">
									<?php wp_nonce_field( 'genzee_add_bundle_' . $source_product_id, 'genzee_bundle_nonce' ); ?>
									<?php foreach ( $product_ids as $product_id ) : ?>
										<input type="hidden" name="genzee_bundle_product_ids[]" value="<?php echo esc_attr( $product_id ); ?>">
									<?php endforeach; ?>
									<button class="lookbook-bundle__button" type="submit" data-pdp-bundle-submit>
										<?php esc_html_e( 'Add all', 'genzee-jewels' ); ?>
									</button>
								</form>
							<?php else : ?>
								<a class="lookbook-bundle__button" href="<?php echo esc_url( $lookbook_shop_url ); ?>">
									<?php esc_html_e( 'Shop look', 'genzee-jewels' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="lookbook-cta" aria-labelledby="lookbook-cta-title">
		<div class="container lookbook-cta__inner">
			<p class="lookbook-cta__eyebrow"><?php esc_html_e( 'Styled you. Now style us.', 'genzee-jewels' ); ?></p>
			<h2 class="lookbook-cta__title" id="lookbook-cta-title">
				<?php esc_html_e( 'Tag your stack', 'genzee-jewels' ); ?>
				<em><?php esc_html_e( ' for the next edit.', 'genzee-jewels' ); ?></em>
			</h2>
			<p class="lookbook-cta__copy">
				<?php esc_html_e( 'Use @genzee.jewels for a chance to be featured and unlock 20% off your next order.', 'genzee-jewels' ); ?>
			</p>
			<a class="lookbook-cta__button" href="<?php echo esc_url( $lookbook_shop_url ); ?>">
				<?php esc_html_e( 'Shop the drop', 'genzee-jewels' ); ?>
			</a>
		</div>
	</section>
</div>

<?php
get_footer();
