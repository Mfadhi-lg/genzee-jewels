<?php
/**
 * Wishlist page template.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="wishlist-page" aria-labelledby="wishlist-page-title">
	<?php
	while ( have_posts() ) :
		the_post();

		if ( '' !== trim( get_post_field( 'post_content', get_the_ID() ) ) ) :
			?>
			<div class="container page-entry__content wishlist-page__content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'genzee-jewels' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
			<?php
		endif;

		get_template_part( 'template-parts/sections/wishlist-grid' );
	endwhile;
	?>
</div>

<?php
get_footer();
