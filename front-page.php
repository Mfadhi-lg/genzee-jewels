<?php
/**
 * Homepage template (front-page.php).
 * WordPress uses this file (not home.php) when a static front page is set.
 *
 * Section order mirrors design_files/home.jsx.
 *
 * @package genzee-jewels
 */

get_header();
?>

	<?php get_template_part( 'template-parts/sections/hero' ); ?>

	<?php get_template_part( 'template-parts/sections/trust-strip' ); ?>

	<?php get_template_part( 'template-parts/sections/categories' ); ?>

	<?php get_template_part( 'template-parts/sections/impulse-zone' ); ?>

	<?php get_template_part( 'template-parts/sections/bestsellers' ); ?>

	<?php get_template_part( 'template-parts/sections/combo-banner' ); ?>

	<?php get_template_part( 'template-parts/sections/new-drops' ); ?>

	<?php get_template_part( 'template-parts/sections/spin-reviews' ); ?>

	<?php get_template_part( 'template-parts/sections/instagram-grid' ); ?>

	<?php get_template_part( 'template-parts/sections/newsletter' ); ?>

	<?php get_template_part( 'template-parts/components/live-toast' ); ?>

<?php
get_footer();
