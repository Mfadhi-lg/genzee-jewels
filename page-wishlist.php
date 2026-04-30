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
	<?php get_template_part( 'template-parts/sections/wishlist-grid' ); ?>
</div>

<?php
get_footer();
