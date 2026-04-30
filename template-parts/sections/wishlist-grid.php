<?php
/**
 * Browser-backed wishlist grid shell.
 *
 * Product IDs live in localStorage, so this section renders an empty shell.
 * assets/js/main.js reads IDs and requests validated product-card HTML.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wishlist_shop_url = home_url( '/shop/' );

if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' ) ) {
	$wishlist_wc_shop_url = wc_get_page_permalink( 'shop' );
	if ( $wishlist_wc_shop_url ) {
		$wishlist_shop_url = $wishlist_wc_shop_url;
	}
}
?>

<section class="wishlist-shell" data-wishlist-page>
	<div class="container wishlist-shell__inner">
		<header class="wishlist-hero">
			<div class="wishlist-hero__copy">
				<p class="wishlist-hero__eyebrow">
					<?php esc_html_e( 'Wishlist', 'genzee-jewels' ); ?>
				</p>
				<h1 id="wishlist-page-title" class="wishlist-hero__title">
					<?php esc_html_e( 'Saved styles', 'genzee-jewels' ); ?>
				</h1>
				<p class="wishlist-hero__subcopy">
					<?php esc_html_e( 'Your favourites, saved for later.', 'genzee-jewels' ); ?>
				</p>
			</div>

			<div class="wishlist-hero__actions">
				<a class="wishlist-hero__link" href="<?php echo esc_url( $wishlist_shop_url ); ?>">
					<?php esc_html_e( 'Continue shopping', 'genzee-jewels' ); ?>
				</a>
				<button class="wishlist-hero__clear" type="button" data-wishlist-clear hidden>
					<?php esc_html_e( 'Clear all', 'genzee-jewels' ); ?>
				</button>
			</div>
		</header>

		<div class="wishlist-status" data-wishlist-status role="status" aria-live="polite">
			<?php esc_html_e( 'Loading saved styles...', 'genzee-jewels' ); ?>
		</div>

		<ul class="wishlist-grid" data-wishlist-grid aria-label="<?php esc_attr_e( 'Saved products', 'genzee-jewels' ); ?>"></ul>

		<div class="wishlist-empty" data-wishlist-empty hidden>
			<p class="wishlist-empty__eyebrow">
				<?php esc_html_e( 'No saved styles yet.', 'genzee-jewels' ); ?>
			</p>
			<p class="wishlist-empty__copy">
				<?php esc_html_e( 'Tap the heart on any product to save it here.', 'genzee-jewels' ); ?>
			</p>
			<a class="wishlist-empty__button" href="<?php echo esc_url( $wishlist_shop_url ); ?>">
				<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
			</a>
		</div>
	</div>
</section>
