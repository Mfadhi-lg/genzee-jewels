<?php
/**
 * Single product template.
 *
 * Replaces the default WooCommerce single-product.php.
 * All product display is handled by our custom template parts;
 * default WC hooks are removed in functions.php.
 *
 * @version 9.4.0
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="pdp">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			global $product;
			$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

			if ( ! $product instanceof WC_Product ) :
				?>
				<div class="container pdp__empty">
					<p><?php esc_html_e( 'This product is currently unavailable.', 'genzee-jewels' ); ?></p>
				</div>
				<?php
				continue;
			endif;

			if ( function_exists( 'woocommerce_output_all_notices' ) ) :
				?>
				<div class="container pdp__notices">
					<?php woocommerce_output_all_notices(); ?>
				</div>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/sections/product-main',
				null,
				array( 'product' => $product )
			);

			get_template_part(
				'template-parts/sections/product-details',
				null,
				array( 'product' => $product )
			);

			get_template_part(
				'template-parts/sections/product-related',
				null,
				array( 'product' => $product )
			);
		endwhile;
	else :
		?>
		<div class="container pdp__empty">
			<p><?php esc_html_e( 'No product was found.', 'genzee-jewels' ); ?></p>
		</div>
		<?php
	endif;
	?>
</div>

<?php
get_footer();
