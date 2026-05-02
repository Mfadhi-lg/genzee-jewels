<?php
/**
 * WooCommerce product archive scaffold.
 *
 * Handles the shop archive and product taxonomy archives using the theme's
 * shared product card component and archive-specific section partials.
 *
 * @version 9.4.0
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$archive_is_wc = class_exists( 'WooCommerce' )
	&& (
		( function_exists( 'is_shop' ) && is_shop() )
		|| is_post_type_archive( 'product' )
		|| is_tax( array( 'product_cat', 'product_tag' ) )
		|| ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() )
	);
?>

<section class="archive-collection" aria-labelledby="archive-collection-title">
	<?php if ( ! $archive_is_wc ) : ?>

		<div class="container archive-collection__main">
			<div class="archive-empty-state">
				<p class="archive-empty-state__eyebrow">
					<?php esc_html_e( 'Collection', 'genzee-jewels' ); ?>
				</p>
				<h1 class="archive-empty-state__title" id="archive-collection-title">
					<?php esc_html_e( 'Shop archive unavailable.', 'genzee-jewels' ); ?>
				</h1>
				<p class="archive-empty-state__copy">
					<?php esc_html_e( 'WooCommerce is not active, so the collection template cannot render products right now.', 'genzee-jewels' ); ?>
				</p>
			</div>
		</div>

	<?php else : ?>

		<?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
			<div class="container">
				<div class="archive-collection__notices">
					<?php woocommerce_output_all_notices(); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/sections/archive-header' ); ?>

		<div class="archive-collection__sticky-tools">
			<div class="archive-collection__tools-inner">
				<?php get_template_part( 'template-parts/sections/archive-toolbar' ); ?>
			</div>
		</div>

		<div class="container archive-collection__main">
			<?php get_template_part( 'template-parts/sections/archive-filters', null, array( 'mode' => 'mobile' ) ); ?>

			<div class="archive-collection__layout">
				<?php get_template_part( 'template-parts/sections/archive-filters', null, array( 'mode' => 'desktop' ) ); ?>

				<div class="archive-collection__content">
					<?php get_template_part( 'template-parts/sections/archive-grid' ); ?>
				</div>
			</div>
		</div>

	<?php endif; ?>
</section>

<?php
get_footer();
