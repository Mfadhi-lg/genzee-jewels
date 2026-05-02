<?php
/**
 * About page template.
 *
 * Template Name: About / Story
 *
 * WordPress loads this template for the /about/ page slug.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="about-story-page" aria-labelledby="about-story-title">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/pages/about-story' );

		if ( '' !== trim( get_post_field( 'post_content', get_the_ID() ) ) ) :
			?>
			<div class="container page-entry__content about-story-page__content">
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
	endwhile;
	?>
</div>

<?php
get_footer();
