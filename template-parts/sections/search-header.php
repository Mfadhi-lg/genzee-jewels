<?php
/**
 * Search results header.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args              = isset( $args ) && is_array( $args ) ? $args : array();
$search_query      = isset( $args['query'] ) ? (string) $args['query'] : get_search_query( false );
$is_product_search = ! empty( $args['is_product_search'] );
$trending_terms    = array(
	'heart hoops',
	'under 199',
	'sets',
	'combo',
	'nose pins',
	'evil eye',
	'layered chain',
);
?>

<section class="search-hero" aria-labelledby="search-page-title">
	<div class="container search-hero__inner">
		<form class="search-hero__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="genzee-product-search">
				<?php esc_html_e( 'Search products', 'genzee-jewels' ); ?>
			</label>
			<span class="search-hero__icon" aria-hidden="true">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" focusable="false">
					<circle cx="11" cy="11" r="7"></circle>
					<path d="M16 16L21 21"></path>
				</svg>
			</span>
			<input
				id="genzee-product-search"
				class="search-hero__input"
				type="search"
				name="s"
				value="<?php echo esc_attr( $search_query ); ?>"
				placeholder="<?php esc_attr_e( 'Search hoops, chains, sets...', 'genzee-jewels' ); ?>"
				autocomplete="off"
			>
			<input type="hidden" name="post_type" value="product">
			<button class="search-hero__submit" type="submit">
				<?php esc_html_e( 'Search', 'genzee-jewels' ); ?>
			</button>
		</form>

		<div class="search-hero__trending" aria-label="<?php esc_attr_e( 'Trending searches', 'genzee-jewels' ); ?>">
			<span class="search-hero__trending-label">
				<?php esc_html_e( 'Trending:', 'genzee-jewels' ); ?>
			</span>
			<div class="search-hero__chips">
				<?php foreach ( $trending_terms as $trending_term ) : ?>
					<?php
					$trend_url   = add_query_arg(
						array(
							's'         => $trending_term,
							'post_type' => 'product',
						),
						home_url( '/' )
					);
					?>
					<a class="search-hero__chip" href="<?php echo esc_url( $trend_url ); ?>">
						<?php echo esc_html( $trending_term ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="search-hero__summary">
			<p class="search-hero__eyebrow">
				<?php echo esc_html( $is_product_search ? __( 'Product search', 'genzee-jewels' ) : __( 'Search', 'genzee-jewels' ) ); ?>
			</p>
			<h1 class="search-hero__title" id="search-page-title">
				<?php if ( '' !== $search_query ) : ?>
					<?php
					printf(
						/* translators: %s: search term */
						esc_html__( 'Showing results for "%s"', 'genzee-jewels' ),
						esc_html( $search_query )
					);
					?>
				<?php else : ?>
					<?php esc_html_e( 'Search Genzee styles', 'genzee-jewels' ); ?>
				<?php endif; ?>
			</h1>
		</div>
	</div>
</section>
