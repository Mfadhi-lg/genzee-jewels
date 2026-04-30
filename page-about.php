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
	<?php get_template_part( 'template-parts/pages/about-story' ); ?>
</div>

<?php
get_footer();
