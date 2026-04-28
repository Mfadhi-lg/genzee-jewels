<?php
/**
 * Price display component.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/price', null, [
 *       'price'   => '₹299', // current / sale price string (required)
 *       'regular' => '₹599', // original / MRP string, omit if not on sale
 *       'off'     => 50,     // discount % integer, omit if 0
 *       'size'    => 'sm',   // 'sm' | omit for default
 *   ] );
 *
 * Accepts pre-formatted price strings so the component stays display-only
 * and works equally with WooCommerce and demo data.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = isset( $args ) && is_array( $args ) ? $args : array();
$price   = isset( $args['price'] )   ? $args['price']   : '';
$regular = isset( $args['regular'] ) ? $args['regular'] : '';
$off     = isset( $args['off'] )     ? (int) $args['off'] : 0;
$size    = ( isset( $args['size'] ) && 'sm' === $args['size'] ) ? ' price--sm' : '';

if ( '' === $price ) {
	return;
}
?>
<div class="price<?php echo esc_attr( $size ); ?>">
	<span class="price__sale"><?php echo wp_kses_post( $price ); ?></span>
	<?php if ( $regular ) : ?>
		<span class="price__regular"><?php echo wp_kses_post( $regular ); ?></span>
	<?php endif; ?>
	<?php if ( $off > 0 ) : ?>
		<span class="price__off">
			<?php
			/* translators: %d: discount percentage e.g. 50 */
			printf( esc_html__( '%d%% OFF', 'genzee-jewels' ), $off );
			?>
		</span>
	<?php endif; ?>
</div>
