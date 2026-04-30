<?php
/**
 * Search results template.
 *
 * Product searches are the default ecommerce search behavior.
 * Explicit non-product post_type searches keep using the standard search loop.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$search_query      = get_search_query( false );
$is_product_search = function_exists( 'genzee_jewels_is_product_search_request' )
	? genzee_jewels_is_product_search_request( $GLOBALS['wp_query'] )
	: false;
?>

<div class="search-page" aria-labelledby="search-page-title">
	<?php
	get_template_part(
		'template-parts/sections/search-header',
		null,
		array(
			'query'             => $search_query,
			'is_product_search' => $is_product_search,
		)
	);

	get_template_part(
		'template-parts/sections/search-grid',
		null,
		array(
			'query'             => $search_query,
			'is_product_search' => $is_product_search,
		)
	);
	?>
</div>

<?php
get_footer();
