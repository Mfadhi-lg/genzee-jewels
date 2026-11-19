<?php
/** Theme bootstrap for the Genzee WooCommerce storefront. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function genzee_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'genzee-prototype' ),
			'footer'  => __( 'Footer navigation', 'genzee-prototype' ),
		)
	);
}
add_action( 'after_setup_theme', 'genzee_setup' );

/**
 * The header navigation is driven by real WooCommerce product categories.
 * They are created once and can then be edited in Products → Categories.
 */
function genzee_seed_navigation_collections() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	$collections = array( 'New In', 'Bestsellers', 'Earrings', 'Necklaces', 'Rings', 'Bracelets', 'Nose Pins', 'Anklets', 'Sets', 'Under ₹299', 'Under ₹399' );
	foreach ( $collections as $collection ) {
		if ( ! term_exists( $collection, 'product_cat' ) ) {
			wp_insert_term( $collection, 'product_cat' );
		}
	}
}
add_action( 'init', 'genzee_seed_navigation_collections', 20 );

/** Return a collection archive URL, with the shop as a reliable fallback. */
function genzee_collection_url( $collection ) {
	$term = get_term_by( 'name', $collection, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		$url = get_term_link( $term );
		if ( ! is_wp_error( $url ) ) {
			return $url;
		}
	}

	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}

/** WordPress-managed navigation until an editor assigns a custom menu. */
function genzee_primary_nav_fallback() {
	$collections = array( 'Earrings', 'Necklaces', 'Rings', 'Bracelets', 'Sets' );

	echo '<a class="desktop-link hot-link" href="' . esc_url( genzee_collection_url( 'New In' ) ) . '">' . esc_html__( 'Just landed', 'genzee-prototype' ) . '</a>';
	echo '<a class="desktop-link" href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ) . '">' . esc_html__( 'Shop all', 'genzee-prototype' ) . '</a>';
	foreach ( $collections as $collection ) {
		echo '<a class="desktop-link" href="' . esc_url( genzee_collection_url( $collection ) ) . '">' . esc_html( $collection ) . '</a>';
	}
	echo '<a class="desktop-link acid-link" href="' . esc_url( genzee_collection_url( 'Under ₹399' ) ) . '">' . esc_html__( 'Under ₹399', 'genzee-prototype' ) . '</a>';
}

/** Load the storefront stylesheet and WooCommerce AJAX cart integration. */
function genzee_enqueue_assets() {
	wp_enqueue_style( 'genzee-storefront', get_stylesheet_uri(), array(), '2.1.0' );
	wp_enqueue_script( 'genzee-navigation', get_theme_file_uri( 'assets/navigation.js' ), array(), '2.1.0', true );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'genzee_enqueue_assets' );

/**
 * The supplied approved design remains the visual source of truth. Only its
 * stylesheet is reused here; all storefront records are rendered by WordPress.
 */
function genzee_design_source() {
	static $source = null;

	if ( null === $source ) {
		$file   = get_theme_file_path( 'prototype.html' );
		$source = is_readable( $file ) ? file_get_contents( $file ) : '';
	}

	return $source;
}

function genzee_output_design_styles() {
	$source = genzee_design_source();
	$offset = 0;

	while ( false !== ( $start = stripos( $source, '<style', $offset ) ) ) {
		$opening_end = strpos( $source, '>', $start );
		$end         = stripos( $source, '</style>', $opening_end );
		if ( false === $opening_end || false === $end ) {
			break;
		}

		echo substr( $source, $start, $end + 8 - $start ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$offset = $end + 8;
	}
}
add_action( 'wp_head', 'genzee_output_design_styles', 99 );

/** Render one real WooCommerce product in the approved homepage card shell. */
function genzee_design_product_card( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	$label = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Jewellery', 'genzee-prototype' );
	?>
	<article class="product genzee-design-product">
		<a class="img" href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>
		</a>
		<div class="pinfo"><div><span class="ptype"><?php echo esc_html( $label ); ?></span><strong><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></strong></div><div class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div></div>
		<?php if ( $product->is_purchasable() && $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
			<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="mini-add add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-quantity="1" rel="nofollow"><?php esc_html_e( 'ADD TO BAG', 'genzee-prototype' ); ?> <b>+</b></a>
		<?php else : ?>
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="mini-add"><?php esc_html_e( 'VIEW PIECE', 'genzee-prototype' ); ?> <b>+</b></a>
		<?php endif; ?>
	</article>
	<?php
}

/** Use WordPress menu records while retaining the approved header link styling. */
class Genzee_Design_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = array( 'desktop-link' );
		$output .= sprintf( '<a class="%1$s" href="%2$s">%3$s</a>', esc_attr( implode( ' ', $classes ) ), esc_url( $item->url ), esc_html( $item->title ) );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/** Replace a complete div's contents without parsing or emitting its prototype data. */
function genzee_replace_design_div_content( $html, $class, $replacement ) {
	$needle = '<div class="' . $class . '"';
	$offset = 0;

	while ( false !== ( $start = strpos( $html, $needle, $offset ) ) ) {
		$open_end = strpos( $html, '>', $start );
		$cursor   = $open_end + 1;
		$depth    = 1;

		while ( $depth > 0 ) {
			$next_open  = strpos( $html, '<div', $cursor );
			$next_close = strpos( $html, '</div>', $cursor );
			if ( false === $next_close ) {
				return $html;
			}
			if ( false !== $next_open && $next_open < $next_close ) {
				$depth++;
				$cursor = $next_open + 4;
			} else {
				$depth--;
				if ( 0 === $depth ) {
					$html   = substr( $html, 0, $open_end + 1 ) . $replacement . substr( $html, $next_close );
					$offset = $open_end + 1 + strlen( $replacement ) + 6;
					break;
				}
				$cursor = $next_close + 6;
			}
		}
	}

	return $html;
}

/** Build the full approved homepage, substituting only WordPress-managed data. */
function genzee_design_home_markup() {
	$source = genzee_design_source();
	$start  = strpos( $source, '<div id="homeView"' );
	$end    = strpos( $source, '<div id="collectionView"', $start );

	if ( false === $start || false === $end ) {
		return '';
	}

	$home = substr( $source, $start, $end - $start );
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	$home = str_replace( '#collectionView', esc_url( $shop ), $home );

	ob_start();
	$categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) );
	if ( $categories && ! is_wp_error( $categories ) ) {
		foreach ( $categories as $index => $category ) {
			$image = wp_get_attachment_image_url( get_term_meta( $category->term_id, 'thumbnail_id', true ), 'woocommerce_thumbnail' );
			printf( '<a class="h13-cat" href="%1$s"><div class="h13-photo"%2$s></div><span><b>%3$s</b><i>%4$s</i></span></a>', esc_url( get_term_link( $category ) ), $image ? ' style="background-image:url(\'' . esc_url( $image ) . '\')"' : '', esc_html( $category->name ), esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ) );
		}
	}
	$category_cards = ob_get_clean();

	ob_start();
	$new = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 8, 'orderby' => 'date', 'order' => 'DESC' ) );
	while ( $new->have_posts() ) {
		$new->the_post();
		genzee_design_product_card( wc_get_product( get_the_ID() ) );
	}
	wp_reset_postdata();
	$new_cards = ob_get_clean();

	ob_start();
	$best = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 4, 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'order' => 'DESC' ) );
	while ( $best->have_posts() ) {
		$best->the_post();
		genzee_design_product_card( wc_get_product( get_the_ID() ) );
	}
	wp_reset_postdata();
	$best_cards = ob_get_clean();

	$home = genzee_replace_design_div_content( $home, 'h13-category-grid', $category_cards );
	$home = genzee_replace_design_div_content( $home, 'products h13-product-grid', $new_cards ? $new_cards : '<p class="genzee-empty-catalogue">' . esc_html__( 'No products have been published yet.', 'genzee-prototype' ) . '</p>' );
	$home = genzee_replace_design_div_content( $home, 'products h13-product-grid four', $best_cards ? $best_cards : '<p class="genzee-empty-catalogue">' . esc_html__( 'No products have been published yet.', 'genzee-prototype' ) . '</p>' );

	return $home;
}

/** Output the WordPress/WooCommerce cart link used in the header. */
function genzee_cart_link() {
	$count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$url   = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
	?>
	<a class="head-action bag-action" href="<?php echo esc_url( $url ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M5 8h14l-1 13H6L5 8Z"></path><path d="M9 9V6a3 3 0 0 1 6 0v3"></path></svg>
		<span><?php esc_html_e( 'Bag', 'genzee-prototype' ); ?></span>
		<i class="bag-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'genzee-prototype' ), $count ) ); ?>"><?php echo esc_html( $count ); ?></i>
	</a>
	<?php
}

/**
 * Saved is presented in the header now; the dedicated wishlist page will be
 * wired when that section is built. A filter keeps the count ready for that
 * integration without inventing a cart or wishlist implementation today.
 */
function genzee_saved_link() {
	$count = (int) apply_filters( 'genzee_saved_items_count', 0 );
	?>
	<a class="head-action saved-action" href="#saved" aria-label="<?php echo esc_attr( sprintf( _n( '%d saved item', '%d saved items', $count, 'genzee-prototype' ), $count ) ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6L12 21l8.8-8.8a5.4 5.4 0 0 0 0-7.6Z"></path></svg>
		<span><?php esc_html_e( 'Saved', 'genzee-prototype' ); ?></span>
		<i class="saved-count" aria-hidden="true"><?php echo esc_html( $count ); ?></i>
	</a>
	<?php
}

/** Refresh the header count after WooCommerce adds an item via AJAX. */
function genzee_cart_link_fragment( $fragments ) {
	ob_start();
	genzee_cart_link();
	$fragments['a.bag-action'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'genzee_cart_link_fragment' );
