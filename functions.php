<?php
/**
 * Genzee Jewels theme functions.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GENZEE_THEME_VERSION' ) ) {
	define( 'GENZEE_THEME_VERSION', '1.0.5' );
}

/**
 * Load helper functions.
 */
require_once get_template_directory() . '/inc/helpers-product.php';

/**
 * Sets up theme defaults and registers support for WordPress features.
 */
function genzee_jewels_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Navigation', 'genzee-jewels' ),
			'footer'  => esc_html__( 'Footer Navigation', 'genzee-jewels' ),
			'mobile'  => esc_html__( 'Mobile Navigation', 'genzee-jewels' ),
		)
	);
}
add_action( 'after_setup_theme', 'genzee_jewels_setup' );

/**
 * Removes default WooCommerce single-product hooks.
 *
 * The theme provides fully custom template parts (product-main.php,
 * product-details.php, product-related.php) so every default WC
 * element must be suppressed to prevent duplicate content.
 */
function genzee_jewels_remove_wc_single_product_hooks() {
	/* ── Before single product summary ────────────────────── */
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

	/* ── Single product summary ───────────────────────────── */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

	/* ── After single product summary (tabs, related, etc.) ── */
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
add_action( 'init', 'genzee_jewels_remove_wc_single_product_hooks' );

/**
 * Enqueues scripts and styles.
 */
function genzee_jewels_scripts() {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	/**
	 * Returns the version for a theme asset.
	 * Uses filemtime in debug mode for cache busting, otherwise uses theme version constant.
	 *
	 * @param string $relative_path Path relative to theme root.
	 * @return string|int
	 */
	$get_asset_version = static function ( $relative_path ) use ( $dir ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$path = $dir . $relative_path;
			if ( file_exists( $path ) ) {
				return filemtime( $path );
			}
		}

		return GENZEE_THEME_VERSION;
	};

	// Base/Global Styles (previously imported in style.css).
	wp_enqueue_style(
		'genzee-base',
		$uri . '/assets/css/base.css',
		array(),
		$get_asset_version( '/assets/css/base.css' )
	);

	wp_enqueue_style(
		'genzee-header',
		$uri . '/assets/css/header.css',
		array( 'genzee-base' ),
		$get_asset_version( '/assets/css/header.css' )
	);

	wp_enqueue_style(
		'genzee-footer',
		$uri . '/assets/css/footer.css',
		array( 'genzee-base' ),
		$get_asset_version( '/assets/css/footer.css' )
	);

	wp_enqueue_style(
		'genzee-components',
		$uri . '/assets/css/components.css',
		array( 'genzee-base' ),
		$get_asset_version( '/assets/css/components.css' )
	);

	// Theme Main Style (header info).
	wp_enqueue_style(
		'genzee-style',
		get_stylesheet_uri(),
		array( 'genzee-base', 'genzee-header', 'genzee-footer', 'genzee-components' ),
		$get_asset_version( '/style.css' )
	);

	// Global functional CSS (needed for cart drawer on all pages).
	wp_enqueue_style(
		'genzee-cart',
		$uri . '/assets/css/cart.css',
		array( 'genzee-style' ),
		$get_asset_version( '/assets/css/cart.css' )
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'genzee-front-page',
			$uri . '/assets/css/front-page.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/front-page.css' )
		);
	}

	if ( is_search() ) {
		wp_enqueue_style(
			'genzee-search',
			$uri . '/assets/css/search.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/search.css' )
		);
	}

	if ( is_page( 'wishlist' ) || is_page_template( 'page-wishlist.php' ) ) {
		wp_enqueue_style(
			'genzee-wishlist',
			$uri . '/assets/css/wishlist.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/wishlist.css' )
		);
	}

	if ( is_page( 'lookbook' ) || is_page_template( 'page-lookbook.php' ) ) {
		wp_enqueue_style(
			'genzee-lookbook',
			$uri . '/assets/css/lookbook.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/lookbook.css' )
		);
	}

	if ( is_page( 'about' ) || is_page_template( 'page-about.php' ) ) {
		wp_enqueue_style(
			'genzee-about',
			$uri . '/assets/css/about.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/about.css' )
		);
	}

	if (
		class_exists( 'WooCommerce' )
		&& function_exists( 'is_account_page' )
		&& is_account_page()
	) {
		wp_enqueue_style(
			'genzee-account',
			$uri . '/assets/css/account.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/account.css' )
		);
	}

	if (
		class_exists( 'WooCommerce' )
		&& function_exists( 'is_checkout' )
		&& is_checkout()
	) {
		wp_enqueue_style(
			'genzee-checkout',
			$uri . '/assets/css/checkout.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/checkout.css' )
		);
	}

	if (
		class_exists( 'WooCommerce' )
		&& function_exists( 'is_shop' )
		&& (
			is_shop()
			|| is_post_type_archive( 'product' )
			|| ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() )
		)
	) {
		wp_enqueue_style(
			'genzee-archive-product',
			$uri . '/assets/css/archive-product.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/archive-product.css' )
		);
	}

	if (
		class_exists( 'WooCommerce' )
		&& function_exists( 'is_product' )
		&& is_product()
	) {
		wp_enqueue_style(
			'genzee-single-product',
			$uri . '/assets/css/single-product.css',
			array( 'genzee-style' ),
			$get_asset_version( '/assets/css/single-product.css' )
		);
	}

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );
	}

	$genzee_main_dependencies = class_exists( 'WooCommerce' )
		? array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' )
		: array();

	wp_enqueue_script(
		'genzee-main',
		$uri . '/assets/js/main.js',
		$genzee_main_dependencies,
		$get_asset_version( '/assets/js/main.js' ),
		true
	);

	wp_localize_script(
		'genzee-main',
		'genzeeWishlist',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'genzee_wishlist_products' ),
			'loadingLabel' => esc_html__( 'Loading saved styles...', 'genzee-jewels' ),
			'errorLabel'   => esc_html__( 'Saved styles could not load. Please refresh and try again.', 'genzee-jewels' ),
			'savedLabel'   => esc_html__( 'Saved to wishlist.', 'genzee-jewels' ),
			'removedLabel' => esc_html__( 'Removed from wishlist.', 'genzee-jewels' ),
			'clearedLabel' => esc_html__( 'Wishlist cleared.', 'genzee-jewels' ),
		)
	);

	wp_localize_script(
		'genzee-main',
		'genzeeCart',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'genzee_cart_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'genzee_jewels_scripts' );

/**
 * Refreshes theme cart count surfaces when WooCommerce AJAX fragments run.
 *
 * @param array<string,string> $fragments Existing fragments.
 * @return array<string,string>
 */
function genzee_jewels_cart_fragments( $fragments ) {
	$cart_count = 0;

	if ( function_exists( 'WC' ) && WC()->cart ) {
		$cart_count = (int) WC()->cart->get_cart_contents_count();
	}

	$fragments['span[data-cart-count]'] = sprintf(
		'<span class="site-header__cart-badge%s" aria-hidden="true" data-cart-count>%s</span>',
		0 === $cart_count ? ' is-empty' : '',
		esc_html( $cart_count )
	);

	$fragments['#cart-drawer-count'] = sprintf(
		'<span class="cart-drawer__count%s" id="cart-drawer-count" aria-live="polite" aria-atomic="true">%s</span>',
		0 === $cart_count ? ' is-empty' : '',
		$cart_count > 0 ? esc_html( '(' . $cart_count . ')' ) : ''
	);

	// Add the drawer inner content fragment.
	ob_start();
	include locate_template( 'template-parts/components/cart-drawer-inner.php' );
	$fragments['#cart-drawer-inner'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'genzee_jewels_cart_fragments' );

/**
 * Handles the PDP multi-item CTA, which adds the displayed products together.
 *
 * @param array<int|string> $raw_product_ids Raw product IDs from the request.
 * @return array<int>|WP_Error
 */
function genzee_jewels_prepare_bundle_product_ids( $raw_product_ids ) {
	$product_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $raw_product_ids ) ) ) );

	if ( empty( $product_ids ) ) {
		return new WP_Error( 'genzee_empty_bundle', __( 'This bundle is currently unavailable.', 'genzee-jewels' ) );
	}

	foreach ( $product_ids as $product_id ) {
		$bundle_product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;

		if ( ! $bundle_product instanceof WC_Product || ! $bundle_product->is_type( 'simple' ) || ! $bundle_product->is_purchasable() || ! $bundle_product->is_in_stock() ) {
			return new WP_Error( 'genzee_invalid_bundle_item', __( 'One of the selected items needs options or is unavailable.', 'genzee-jewels' ) );
		}
	}

	return $product_ids;
}

/**
 * Handles the PDP multi-item CTA, which adds the displayed products together.
 */
function genzee_jewels_handle_bundle_add_to_cart() {
	if ( empty( $_POST['genzee_add_bundle'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}

	$source_product_id = isset( $_POST['genzee_add_bundle'] ) ? absint( wp_unslash( $_POST['genzee_add_bundle'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$nonce             = isset( $_POST['genzee_bundle_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['genzee_bundle_nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$return_url        = $source_product_id > 0 ? get_permalink( $source_product_id ) : wp_get_referer();

	if ( ! $return_url ) {
		$return_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' );
	}

	if ( ! $source_product_id || ! wp_verify_nonce( $nonce, 'genzee_add_bundle_' . $source_product_id ) ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'We could not add those items. Please refresh the page and try again.', 'genzee-jewels' ), 'error' );
		}

		wp_safe_redirect( $return_url );
		exit;
	}

	$raw_product_ids = isset( $_POST['genzee_bundle_product_ids'] ) ? (array) wp_unslash( $_POST['genzee_bundle_product_ids'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$product_ids     = genzee_jewels_prepare_bundle_product_ids( $raw_product_ids );

	if ( is_wp_error( $product_ids ) ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( $product_ids->get_error_message(), 'error' );
		}

		wp_safe_redirect( $return_url );
		exit;
	}

	$added_count = 0;

	foreach ( $product_ids as $product_id ) {
		if ( WC()->cart->add_to_cart( $product_id, 1 ) ) {
			$added_count++;
		}
	}

	if ( $added_count === count( $product_ids ) ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'Items added to your cart.', 'genzee-jewels' ), 'success' );
		}

		wp_safe_redirect( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $return_url );
		exit;
	}

	if ( function_exists( 'wc_add_notice' ) ) {
		wc_add_notice( __( 'We could not add all items. Please review your cart.', 'genzee-jewels' ), 'error' );
	}

	wp_safe_redirect( $return_url );
	exit;
}
add_action( 'wp_loaded', 'genzee_jewels_handle_bundle_add_to_cart', 20 );

/**
 * AJAX version of the PDP bundle add-to-cart action.
 */
function genzee_jewels_ajax_add_bundle_to_cart() {
	check_ajax_referer( 'genzee_cart_nonce', 'nonce' );

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error(
			array( 'message' => __( 'Cart is not available right now. Please refresh and try again.', 'genzee-jewels' ) ),
			500
		);
	}

	$raw_product_ids = array();

	if ( isset( $_POST['product_ids'] ) ) {
		$product_ids_request = wp_unslash( $_POST['product_ids'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$raw_product_ids     = is_array( $product_ids_request )
			? $product_ids_request
			: preg_split( '/[\s,]+/', sanitize_text_field( $product_ids_request ) );
	}

	$product_ids = genzee_jewels_prepare_bundle_product_ids( $raw_product_ids );

	if ( is_wp_error( $product_ids ) ) {
		wp_send_json_error(
			array( 'message' => $product_ids->get_error_message() ),
			400
		);
	}

	$added_count = 0;

	foreach ( $product_ids as $product_id ) {
		if ( WC()->cart->add_to_cart( $product_id, 1 ) ) {
			$added_count++;
		}
	}

	if ( $added_count !== count( $product_ids ) ) {
		wp_send_json_error(
			array( 'message' => __( 'We could not add all items. Please review your cart.', 'genzee-jewels' ) ),
			400
		);
	}

	if ( class_exists( 'WC_AJAX' ) ) {
		WC_AJAX::get_refreshed_fragments();
	}

	wp_send_json_success(
		array(
			'message'   => __( 'Items added to your cart.', 'genzee-jewels' ),
			'cart_hash' => WC()->cart->get_cart_hash(),
		)
	);
}
add_action( 'wp_ajax_genzee_add_bundle_to_cart', 'genzee_jewels_ajax_add_bundle_to_cart' );
add_action( 'wp_ajax_nopriv_genzee_add_bundle_to_cart', 'genzee_jewels_ajax_add_bundle_to_cart' );

/**
 * Determines whether the current search request should be handled as a product search.
 *
 * Product search is the ecommerce default for this theme. Explicit non-product
 * post_type requests still keep normal WordPress search behavior.
 *
 * @param WP_Query|null $query Optional query object.
 * @return bool
 */
function genzee_jewels_is_product_search_request( $query = null ) {
	if ( ! class_exists( 'WooCommerce' ) || ! post_type_exists( 'product' ) ) {
		return false;
	}

	$is_search = $query instanceof WP_Query ? $query->is_search() : is_search();

	if ( ! $is_search ) {
		return false;
	}

	$requested_post_type = null;

	if ( $query instanceof WP_Query ) {
		$query_post_type = $query->get( 'post_type' );

		if ( ! empty( $query_post_type ) ) {
			$requested_post_type = $query_post_type;
		}
	}

	if ( null === $requested_post_type && isset( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$requested_post_type = wp_unslash( $_GET['post_type'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( is_array( $requested_post_type ) ) {
		$requested_post_type = array_map( 'sanitize_key', $requested_post_type );

		return in_array( 'product', $requested_post_type, true );
	}

	if ( null === $requested_post_type || '' === $requested_post_type ) {
		return true;
	}

	return 'product' === sanitize_key( $requested_post_type );
}

/**
 * Forces ecommerce search requests to query published WooCommerce products.
 *
 * @param WP_Query $query Query object.
 */
function genzee_jewels_product_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! genzee_jewels_is_product_search_request( $query ) ) {
		return;
	}

	$query->set( 'post_type', 'product' );
	$query->set( 'post_status', 'publish' );
	$query->set( 'ignore_sticky_posts', true );
}
add_action( 'pre_get_posts', 'genzee_jewels_product_search_query' );

/**
 * Keeps product searches on the theme search template instead of the product archive template.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function genzee_jewels_product_search_template( $template ) {
	if ( ! genzee_jewels_is_product_search_request( $GLOBALS['wp_query'] ) ) {
		return $template;
	}

	$search_template = locate_template( 'search.php' );

	return $search_template ? $search_template : $template;
}
add_filter( 'template_include', 'genzee_jewels_product_search_template', 20 );

/**
 * Returns validated product-card HTML for browser-stored wishlist IDs.
 *
 * This endpoint is read-only and serves only public product data, so it
 * relaxes strict nonce requirements to remain reliable on cached pages.
 */
function genzee_jewels_wishlist_products() {
	// Optional: we can still check the nonce if provided, but don't fail if it's missing/expired
	// for public product data as long as the request is safe.
	// Since we strictly filter by 'publish' status and 'product' post type, this is safe.

	$raw_ids = isset( $_POST['ids'] ) ? wp_unslash( $_POST['ids'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( is_string( $raw_ids ) ) {
		$decoded_ids = json_decode( $raw_ids, true );
		$raw_ids     = is_array( $decoded_ids ) ? $decoded_ids : explode( ',', $raw_ids );
	}

	if ( ! is_array( $raw_ids ) ) {
		$raw_ids = array();
	}

	$ids = array();
	foreach ( $raw_ids as $raw_id ) {
		$id = absint( $raw_id );
		if ( $id > 0 && ! in_array( $id, $ids, true ) ) {
			$ids[] = $id;
		}
	}

	$ids = array_slice( $ids, 0, 60 );

	if (
		empty( $ids )
		|| ! class_exists( 'WooCommerce' )
		|| ! function_exists( 'wc_get_product' )
		|| ! post_type_exists( 'product' )
	) {
		$wishlist_unavailable = ! empty( $ids );

		wp_send_json_success(
			array(
				'html'        => '',
				'ids'         => array(),
				'unavailable' => $wishlist_unavailable,
			)
		);
	}

	$wishlist_query = new WP_Query(
		array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'post__in'            => $ids,
			'orderby'             => 'post__in',
			'posts_per_page'      => count( $ids ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$rendered_ids = array();
	$html         = '';

	if ( $wishlist_query->have_posts() ) {
		ob_start();

		while ( $wishlist_query->have_posts() ) {
			$wishlist_query->the_post();

			$product = wc_get_product( get_the_ID() );

			if ( ! $product || 'publish' !== get_post_status( $product->get_id() ) ) {
				continue;
			}

			$rendered_ids[] = (int) $product->get_id();
			?>
			<li class="wishlist-grid__item" data-wishlist-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
				<?php
				get_template_part(
					'template-parts/components/product-card',
					null,
					array(
						'product' => $product,
					)
				);
				?>
			</li>
			<?php
		}

		$html = ob_get_clean();
		wp_reset_postdata();

	}

	wp_send_json_success(
		array(
			'html'        => $html,
			'ids'         => $rendered_ids,
			'unavailable' => false,
		)
	);
}
add_action( 'wp_ajax_genzee_wishlist_products', 'genzee_jewels_wishlist_products' );
add_action( 'wp_ajax_nopriv_genzee_wishlist_products', 'genzee_jewels_wishlist_products' );

/**
 * Updates cart item quantity via AJAX for the cart drawer.
 */
function genzee_update_cart_item_qty() {
	check_ajax_referer( 'genzee_cart_nonce', 'nonce' );

	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'WooCommerce not active.', 'genzee-jewels' ) ) );
	}

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
	$new_qty       = isset( $_POST['quantity'] ) ? (float) $_POST['quantity'] : 1;

	if ( ! $cart_item_key || ! isset( WC()->cart->get_cart()[ $cart_item_key ] ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid cart item.', 'genzee-jewels' ) ) );
	}

	if ( $new_qty <= 0 ) {
		WC()->cart->remove_cart_item( $cart_item_key );
	} else {
		WC()->cart->set_quantity( $cart_item_key, $new_qty );
	}

	// Calculate totals.
	WC()->cart->calculate_totals();

	// Return fragments so the UI updates.
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wp_ajax_genzee_update_cart_item_qty', 'genzee_update_cart_item_qty' );
add_action( 'wp_ajax_nopriv_genzee_update_cart_item_qty', 'genzee_update_cart_item_qty' );

/**
 * Adds the hot class to the primary nav item that contains "299".
 *
 * @param string[] $classes Existing CSS classes.
 * @param WP_Post  $item    Nav menu item post object.
 * @param stdClass $args    wp_nav_menu() args.
 * @param int      $depth   Menu depth.
 * @return string[]
 */
function genzee_jewels_nav_item_hot_class( $classes, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		if ( false !== strpos( $item->title, '299' ) ) {
			$classes[] = 'menu-item--hot';
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'genzee_jewels_nav_item_hot_class', 10, 4 );

/**
 * Returns the published wishlist page URL when the theme can safely identify one.
 *
 * @return string
 */
function genzee_jewels_get_wishlist_page_url() {
	$wishlist_page = get_page_by_path( 'wishlist' );

	if ( $wishlist_page instanceof WP_Post && 'publish' === get_post_status( $wishlist_page ) ) {
		return get_permalink( $wishlist_page );
	}

	$wishlist_template_pages = get_posts(
		array(
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-wishlist.php',
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'posts_per_page' => 1,
		)
	);

	if ( ! empty( $wishlist_template_pages[0] ) ) {
		return get_permalink( (int) $wishlist_template_pages[0] );
	}

	return '';
}

/**
 * Returns a safe support URL for account navigation.
 *
 * @return string
 */
function genzee_jewels_get_account_support_url() {
	$account_url = home_url( '/my-account/' );

	if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_account_endpoint_url' ) ) {
		$account_url = wc_get_account_endpoint_url( 'dashboard' );
	}

	return $account_url . '#genzee-account-support';
}

/**
 * Adds non-endpoint account navigation helpers without replacing WooCommerce items.
 *
 * @param string[] $items Existing account menu items.
 * @return string[]
 */
function genzee_jewels_account_menu_items( $items ) {
	$enhanced_items = array();
	$wishlist_url   = genzee_jewels_get_wishlist_page_url();

	foreach ( $items as $endpoint => $label ) {
		if ( 'customer-logout' === $endpoint ) {
			if ( $wishlist_url ) {
				$enhanced_items['genzee-wishlist'] = __( 'Wishlist', 'genzee-jewels' );
			}

			$enhanced_items['genzee-support'] = __( 'Help / Support', 'genzee-jewels' );
		}

		$enhanced_items[ $endpoint ] = $label;
	}

	if ( ! isset( $items['customer-logout'] ) ) {
		if ( $wishlist_url ) {
			$enhanced_items['genzee-wishlist'] = __( 'Wishlist', 'genzee-jewels' );
		}

		$enhanced_items['genzee-support'] = __( 'Help / Support', 'genzee-jewels' );
	}

	return $enhanced_items;
}
add_filter( 'woocommerce_account_menu_items', 'genzee_jewels_account_menu_items', 20 );

/**
 * Resolves account helper menu items to static URLs.
 *
 * @param string $url       Endpoint URL.
 * @param string $endpoint  Endpoint key.
 * @param string $value     Endpoint value.
 * @param string $permalink Account permalink.
 * @return string
 */
function genzee_jewels_account_helper_endpoint_urls( $url, $endpoint, $value, $permalink ) {
	unset( $value, $permalink );

	if ( 'genzee-wishlist' === $endpoint ) {
		$wishlist_url = genzee_jewels_get_wishlist_page_url();

		return $wishlist_url ? $wishlist_url : $url;
	}

	if ( 'genzee-support' === $endpoint ) {
		return genzee_jewels_get_account_support_url();
	}

	return $url;
}
add_filter( 'woocommerce_get_endpoint_url', 'genzee_jewels_account_helper_endpoint_urls', 10, 4 );

/**
 * Customizes WooCommerce checkout field classes for side-by-side layout.
 *
 * @param array $fields Existing checkout fields.
 * @return array
 */
function genzee_jewels_checkout_fields( $fields ) {
	// Billing fields.
	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_first_name']['label'] = __( 'First name', 'woocommerce' );
		$fields['billing']['billing_first_name']['placeholder'] = '';
	}
	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		$fields['billing']['billing_last_name']['class'] = array( 'form-row-last' );
		$fields['billing']['billing_last_name']['label'] = __( 'Last name', 'woocommerce' );
		$fields['billing']['billing_last_name']['placeholder'] = '';
	}
	if ( isset( $fields['billing']['billing_company'] ) ) {
		$fields['billing']['billing_company']['class'] = array( 'form-row-wide' );
		$fields['billing']['billing_company']['label'] = __( 'Company name', 'woocommerce' );
		$fields['billing']['billing_company']['placeholder'] = __( 'Company name (optional)', 'genzee-jewels' );
	}
	if ( isset( $fields['billing']['billing_country'] ) ) {
		$fields['billing']['billing_country']['class'] = array( 'form-row-wide' );
		$fields['billing']['billing_country']['label'] = __( 'Country / Region', 'woocommerce' );
	}
	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['class'] = array( 'form-row-wide' );
		$fields['billing']['billing_address_1']['label'] = __( 'Street address', 'woocommerce' );
		$fields['billing']['billing_address_1']['placeholder'] = __( 'House number and street name', 'genzee-jewels' );
	}
	if ( isset( $fields['billing']['billing_address_2'] ) ) {
		$fields['billing']['billing_address_2']['class'] = array( 'form-row-wide' );
		$fields['billing']['billing_address_2']['label'] = '';
		$fields['billing']['billing_address_2']['placeholder'] = __( 'Apartment, suite, unit (optional)', 'genzee-jewels' );
		$fields['billing']['billing_address_2']['required'] = false;
	}
	if ( isset( $fields['billing']['billing_city'] ) ) {
		$fields['billing']['billing_city']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_city']['label'] = __( 'Town / City', 'woocommerce' );
		$fields['billing']['billing_city']['placeholder'] = '';
	}
	if ( isset( $fields['billing']['billing_state'] ) ) {
		$fields['billing']['billing_state']['class'] = array( 'form-row-last' );
		$fields['billing']['billing_state']['label'] = __( 'State', 'woocommerce' );
		$fields['billing']['billing_state']['placeholder'] = __( 'Select state', 'genzee-jewels' );
	}
	if ( isset( $fields['billing']['billing_postcode'] ) ) {
		$fields['billing']['billing_postcode']['class'] = array( 'form-row-wide' );
		$fields['billing']['billing_postcode']['label'] = __( 'PIN Code', 'genzee-jewels' );
		$fields['billing']['billing_postcode']['placeholder'] = '';
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_phone']['label'] = __( 'Mobile number', 'genzee-jewels' );
		$fields['billing']['billing_phone']['placeholder'] = __( '+91', 'genzee-jewels' );
		$fields['billing']['billing_phone']['required'] = true;
	}
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['class'] = array( 'form-row-last' );
		$fields['billing']['billing_email']['label'] = __( 'Email address', 'woocommerce' );
		$fields['billing']['billing_email']['placeholder'] = '';
		$fields['billing']['billing_email']['required'] = true;
	}

	// Shipping fields.
	if ( isset( $fields['shipping']['shipping_first_name'] ) ) {
		$fields['shipping']['shipping_first_name']['class'] = array( 'form-row-first' );
		$fields['shipping']['shipping_first_name']['placeholder'] = '';
	}
	if ( isset( $fields['shipping']['shipping_last_name'] ) ) {
		$fields['shipping']['shipping_last_name']['class'] = array( 'form-row-last' );
		$fields['shipping']['shipping_last_name']['placeholder'] = '';
	}
	if ( isset( $fields['shipping']['shipping_company'] ) ) {
		$fields['shipping']['shipping_company']['class'] = array( 'form-row-wide' );
		$fields['shipping']['shipping_company']['placeholder'] = __( 'Company name (optional)', 'genzee-jewels' );
	}
	if ( isset( $fields['shipping']['shipping_country'] ) ) {
		$fields['shipping']['shipping_country']['class'] = array( 'form-row-wide' );
		$fields['shipping']['shipping_country']['label'] = __( 'Country / Region', 'woocommerce' );
	}
	if ( isset( $fields['shipping']['shipping_address_1'] ) ) {
		$fields['shipping']['shipping_address_1']['class'] = array( 'form-row-wide' );
		$fields['shipping']['shipping_address_1']['label'] = __( 'Street address', 'woocommerce' );
		$fields['shipping']['shipping_address_1']['placeholder'] = __( 'House number and street name', 'genzee-jewels' );
	}
	if ( isset( $fields['shipping']['shipping_address_2'] ) ) {
		$fields['shipping']['shipping_address_2']['class'] = array( 'form-row-wide' );
		$fields['shipping']['shipping_address_2']['label'] = '';
		$fields['shipping']['shipping_address_2']['placeholder'] = __( 'Apartment, suite, unit (optional)', 'genzee-jewels' );
		$fields['shipping']['shipping_address_2']['required'] = false;
	}
	if ( isset( $fields['shipping']['shipping_city'] ) ) {
		$fields['shipping']['shipping_city']['class'] = array( 'form-row-first' );
		$fields['shipping']['shipping_city']['label'] = __( 'Town / City', 'woocommerce' );
		$fields['shipping']['shipping_city']['placeholder'] = '';
	}
	if ( isset( $fields['shipping']['shipping_state'] ) ) {
		$fields['shipping']['shipping_state']['class'] = array( 'form-row-last' );
		$fields['shipping']['shipping_state']['placeholder'] = __( 'Select state', 'genzee-jewels' );
	}
	if ( isset( $fields['shipping']['shipping_postcode'] ) ) {
		$fields['shipping']['shipping_postcode']['class'] = array( 'form-row-wide' );
		$fields['shipping']['shipping_postcode']['label'] = __( 'PIN Code', 'genzee-jewels' );
		$fields['shipping']['shipping_postcode']['placeholder'] = '';
	}

	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['class'] = array( 'form-row-wide' );
		$fields['order']['order_comments']['label'] = __( 'Delivery note (optional)', 'genzee-jewels' );
		$fields['order']['order_comments']['placeholder'] = __( 'Gate code, delivery preference, or gifting note', 'genzee-jewels' );
		$fields['order']['order_comments']['required'] = false;
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'genzee_jewels_checkout_fields' );

/**
 * Keeps the checkout CTA clear and consistent.
 *
 * @return string
 */
function genzee_jewels_checkout_order_button_text() {
	return __( 'Place order', 'woocommerce' );
}
add_filter( 'woocommerce_order_button_text', 'genzee_jewels_checkout_order_button_text' );

/**
 * Matches the checkout summary wording used in the compact design.
 *
 * @param string $name Existing package label.
 * @return string
 */
function genzee_jewels_checkout_shipping_package_name( $name ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return __( 'Shipment', 'genzee-jewels' );
	}

	return $name;
}
add_filter( 'woocommerce_shipping_package_name', 'genzee_jewels_checkout_shipping_package_name' );

/**
 * Uses the collection design's customer-facing sort labels.
 *
 * @param array<string,string> $options WooCommerce catalog ordering labels.
 * @return array<string,string>
 */
function genzee_jewels_catalog_orderby_labels( $options ) {
	if ( isset( $options['menu_order'] ) ) {
		$options['menu_order'] = __( 'Trending', 'genzee-jewels' );
	}
	if ( isset( $options['popularity'] ) ) {
		$options['popularity'] = __( 'Popular', 'genzee-jewels' );
	}
	if ( isset( $options['date'] ) ) {
		$options['date'] = __( 'Newest', 'genzee-jewels' );
	}
	if ( isset( $options['price'] ) ) {
		$options['price'] = __( 'Price: Low to High', 'genzee-jewels' );
	}
	if ( isset( $options['price-desc'] ) ) {
		$options['price-desc'] = __( 'Price: High to Low', 'genzee-jewels' );
	}

	return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'genzee_jewels_catalog_orderby_labels' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'genzee_jewels_catalog_orderby_labels' );

/**
 * Adds trust badges to the checkout order review section.
 */
function genzee_jewels_checkout_trust_badges() {
	?>
	<div class="checkout-trust-badges">
		<div class="checkout-trust-badge">
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
				<path d="M12 3l7 3v5c0 4.5-2.8 8.5-7 10-4.2-1.5-7-5.5-7-10V6l7-3z"></path>
				<path d="M9 12l2 2 4-5"></path>
			</svg>
			<span><?php esc_html_e( 'Secure checkout', 'genzee-jewels' ); ?></span>
		</div>
		<div class="checkout-trust-badge">
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
				<rect x="3" y="5" width="18" height="14" rx="2"></rect>
				<path d="M3 10h18"></path>
			</svg>
			<span><?php esc_html_e( 'Payment options shown at checkout', 'genzee-jewels' ); ?></span>
		</div>
		<div class="checkout-trust-badge">
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
				<path d="M3 7h11v9H3z"></path>
				<path d="M14 10h4l3 3v3h-7z"></path>
				<circle cx="7" cy="18" r="1.5"></circle>
				<circle cx="17" cy="18" r="1.5"></circle>
			</svg>
			<span><?php esc_html_e( 'Shipping calculated by WooCommerce', 'genzee-jewels' ); ?></span>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_checkout_order_review', 'genzee_jewels_checkout_trust_badges', 15 );

/**
 * Returns the active metal attribute taxonomy info, or false if none exists with terms.
 *
 * @return array{taxonomy: string, param: string}|false
 */
function genzee_jewels_get_metal_taxonomy() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	if ( taxonomy_exists( 'pa_metal' ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'pa_metal',
				'hide_empty' => true,
				'number'     => 1,
			)
		);
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$cache = array(
				'taxonomy' => 'pa_metal',
				'param'    => 'filter_metal',
			);
			return $cache;
		}
	}
	$cache = false;
	return false;
}

/**
 * Returns the active colour attribute taxonomy info, or false if none exists with terms.
 * Checks pa_color first, then pa_colour.
 *
 * @return array{taxonomy: string, param: string}|false
 */
function genzee_jewels_get_color_taxonomy() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	foreach ( array( 'pa_color', 'pa_colour' ) as $try_tax ) {
		if ( taxonomy_exists( $try_tax ) ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $try_tax,
					'hide_empty' => true,
					'number'     => 1,
				)
			);
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$cache = array(
					'taxonomy' => $try_tax,
					'param'    => 'filter_' . str_replace( 'pa_', '', $try_tax ),
				);
				return $cache;
			}
		}
	}
	$cache = false;
	return false;
}

/**
 * Applies metal and colour attribute tax_query items to the WooCommerce product archive query.
 *
 * Price (min_price/max_price) and rating (rating_filter) are handled natively by WC_Query.
 *
 * @param WP_Query $q The main WooCommerce product query.
 */
function genzee_jewels_product_query_filters( $q ) {
	$metal_info = genzee_jewels_get_metal_taxonomy();
	$color_info = genzee_jewels_get_color_taxonomy();

	if ( ! $metal_info && ! $color_info ) {
		return;
	}

	$tax_query = (array) $q->get( 'tax_query' );
	$modified  = false;

	if ( $metal_info ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$raw = isset( $_GET[ $metal_info['param'] ] )
			? sanitize_title( wp_unslash( $_GET[ $metal_info['param'] ] ) )
			: '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		if ( '' !== $raw ) {
			$slugs = array_values( array_filter( array_map( 'sanitize_title', explode( ',', $raw ) ) ) );
			if ( ! empty( $slugs ) ) {
				$tax_query[] = array(
					'taxonomy' => $metal_info['taxonomy'],
					'field'    => 'slug',
					'terms'    => $slugs,
					'operator' => 'IN',
				);
				$modified    = true;
			}
		}
	}

	if ( $color_info ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$raw = isset( $_GET[ $color_info['param'] ] )
			? sanitize_title( wp_unslash( $_GET[ $color_info['param'] ] ) )
			: '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		if ( '' !== $raw ) {
			$slugs = array_values( array_filter( array_map( 'sanitize_title', explode( ',', $raw ) ) ) );
			if ( ! empty( $slugs ) ) {
				$tax_query[] = array(
					'taxonomy' => $color_info['taxonomy'],
					'field'    => 'slug',
					'terms'    => $slugs,
					'operator' => 'IN',
				);
				$modified    = true;
			}
		}
	}

	if ( $modified ) {
		$q->set( 'tax_query', $tax_query );
	}
}
add_action( 'woocommerce_product_query', 'genzee_jewels_product_query_filters' );
