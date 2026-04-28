<?php
/**
 * Genzee Jewels theme functions.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Theme Setup ───────────────────────────────────────────────────────────────

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function genzee_jewels_setup() {
	// Let WordPress manage the document <title>.
	add_theme_support( 'title-tag' );

	// Enable post thumbnail support.
	add_theme_support( 'post-thumbnails' );

	// Custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// HTML5 markup for built-in elements.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Enable Customizer widget refresh without full-page reload.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// WooCommerce support — only registered when WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// Navigation menus.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Navigation', 'genzee-jewels' ),
		'footer'  => esc_html__( 'Footer Navigation', 'genzee-jewels' ),
		'mobile'  => esc_html__( 'Mobile Navigation', 'genzee-jewels' ),
	) );
}
add_action( 'after_setup_theme', 'genzee_jewels_setup' );

// ── Asset Enqueueing ──────────────────────────────────────────────────────────

/**
 * Enqueues scripts and styles.
 * Assets are versioned using filemtime() to bust the browser cache on change.
 */
function genzee_jewels_scripts() {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	// 1. style.css — WordPress theme header + fallback.
	wp_enqueue_style(
		'genzee-style',
		get_stylesheet_uri(),
		array(),
		filemtime( $dir . '/style.css' )
	);

	// 2. base.css — CSS variables, reset, typography, containers, utilities.
	wp_enqueue_style(
		'genzee-base',
		$uri . '/assets/css/base.css',
		array( 'genzee-style' ),
		filemtime( $dir . '/assets/css/base.css' )
	);

	// 3. header.css — Site header styles.
	wp_enqueue_style(
		'genzee-header',
		$uri . '/assets/css/header.css',
		array( 'genzee-base' ),
		filemtime( $dir . '/assets/css/header.css' )
	);

	// 4. footer.css — Site footer styles.
	wp_enqueue_style(
		'genzee-footer',
		$uri . '/assets/css/footer.css',
		array( 'genzee-base' ),
		filemtime( $dir . '/assets/css/footer.css' )
	);

	// 5. components.css — Reusable UI components (product card, stars, price).
	wp_enqueue_style(
		'genzee-components',
		$uri . '/assets/css/components.css',
		array( 'genzee-base' ),
		filemtime( $dir . '/assets/css/components.css' )
	);

	// 6. front-page.css — Homepage section styles.
	wp_enqueue_style(
		'genzee-front-page',
		$uri . '/assets/css/front-page.css',
		array( 'genzee-components' ),
		filemtime( $dir . '/assets/css/front-page.css' )
	);

	// main.js — Mobile nav toggle and future UX enhancements.
	wp_enqueue_script(
		'genzee-main',
		$uri . '/assets/js/main.js',
		array(),
		filemtime( $dir . '/assets/js/main.js' ),
		true // Load in footer.
	);
}
add_action( 'wp_enqueue_scripts', 'genzee_jewels_scripts' );

// ── Nav helpers ───────────────────────────────────────────────────────────────

/**
 * Adds menu-item--hot class to nav items that contain "299" in the title.
 * Drives the HOT badge on the "Under ₹299" link via CSS ::after.
 *
 * @param string[] $classes  Existing CSS classes.
 * @param WP_Post  $item     Nav menu item post object.
 * @param stdClass $args     wp_nav_menu() args.
 * @param int      $depth    Menu depth.
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
