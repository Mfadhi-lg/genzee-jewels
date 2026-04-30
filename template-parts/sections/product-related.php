<?php
/**
 * PDP related products section.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = isset( $args ) && is_array( $args ) ? $args : array();
$product = isset( $args['product'] ) && $args['product'] instanceof WC_Product ? $args['product'] : null;

if ( ! $product ) {
	return;
}

$related_ids = function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $product->get_id(), 4 ) : array();

if ( empty( $related_ids ) ) {
	$fallback = wc_get_products(
		array(
			'exclude' => array( $product->get_id() ),
			'limit'   => 4,
			'status'  => 'publish',
			'return'  => 'ids',
		)
	);

	$related_ids = is_array( $fallback ) ? $fallback : array();
}

?>

<section class="pdp-related" aria-labelledby="pdp-related-title">
	<div class="container">
		<h2 class="pdp-related__title" id="pdp-related-title">
			<?php esc_html_e( 'You may also', 'genzee-jewels' ); ?>
			<em class="pdp-related__title-em"><?php esc_html_e( ' obsess over.', 'genzee-jewels' ); ?></em>
		</h2>

		<?php if ( ! empty( $related_ids ) ) : ?>
			<div class="pdp-related__grid">
				<?php foreach ( $related_ids as $related_id ) : ?>
					<?php
					$related_product = wc_get_product( $related_id );

					if ( ! $related_product instanceof WC_Product || ! $related_product->is_visible() ) {
						continue;
					}

					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'product' => $related_product )
					);
					?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
