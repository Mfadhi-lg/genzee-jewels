<?php
/**
 * Archive product grid section.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_shop_url = class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );

$archive_has_posts = have_posts();
$archive_rendered = 0;
?>

<section class="archive-grid archive-grid--grid4" data-archive-grid aria-label="<?php esc_attr_e( 'Collection products', 'genzee-jewels' ); ?>">
	<?php if ( $archive_has_posts ) : ?>
		<ul class="archive-grid__products" aria-label="<?php esc_attr_e( 'Products', 'genzee-jewels' ); ?>">
			<?php while ( have_posts() ) : ?>
				<?php
				the_post();

				$archive_product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

				if ( ! $archive_product ) {
					continue;
				}

				$archive_rendered++;
				?>
				<li class="archive-grid__item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array(
							'product' => $archive_product,
						)
					);
					?>
				</li>

				<?php if ( 4 === $archive_rendered ) : ?>
				<li class="archive-grid__item archive-grid__item--promo-wide">
					<?php get_template_part( 'template-parts/components/promo-tile', null, array( 'type' => 'flash' ) ); ?>
				</li>
				<?php endif; ?>

				<?php if ( 10 === $archive_rendered ) : ?>
				<li class="archive-grid__item archive-grid__item--promo">
					<?php get_template_part( 'template-parts/components/promo-tile', null, array( 'type' => 'shop-look' ) ); ?>
				</li>
				<?php endif; ?>

			<?php endwhile; ?>
		</ul>

		<?php if ( $archive_rendered > 0 ) : ?>
			<div class="archive-grid__pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => esc_html__( '←', 'genzee-jewels' ),
						'next_text'          => esc_html__( '→', 'genzee-jewels' ),
						'screen_reader_text' => esc_html__( 'Collection pagination', 'genzee-jewels' ),
					)
				);
				?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( ! $archive_has_posts || 0 === $archive_rendered ) : ?>
		<div class="archive-empty-state">
			<p class="archive-empty-state__eyebrow">
				<?php esc_html_e( 'No matches', 'genzee-jewels' ); ?>
			</p>
			<h2 class="archive-empty-state__title">
				<?php esc_html_e( 'No styles found.', 'genzee-jewels' ); ?>
			</h2>
			<p class="archive-empty-state__copy">
				<?php esc_html_e( 'Try clearing filters or browse all jewellery.', 'genzee-jewels' ); ?>
			</p>
			<a class="archive-empty-state__link" href="<?php echo esc_url( $archive_shop_url ); ?>">
				<?php esc_html_e( 'View all products', 'genzee-jewels' ); ?>
			</a>
		</div>
	<?php endif; ?>
</section>
