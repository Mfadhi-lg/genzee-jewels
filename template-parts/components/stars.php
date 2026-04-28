<?php
/**
 * Stars rating component.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/stars', null, [
 *       'rating' => 4.8,  // float 0–5
 *       'size'   => 'sm', // 'sm' (10 px icons) | omit for default 12 px
 *   ] );
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args   = isset( $args ) && is_array( $args ) ? $args : array();
$rating = isset( $args['rating'] ) ? (float) $args['rating'] : 5.0;
$size   = ( isset( $args['size'] ) && 'sm' === $args['size'] ) ? ' stars--sm' : '';
$filled = (int) round( $rating );
?>
<span class="stars<?php echo esc_attr( $size ); ?>"
	role="img"
	aria-label="<?php
		/* translators: %s: rating value e.g. "4.8" */
		printf( esc_attr__( 'Rating: %s out of 5 stars', 'genzee-jewels' ), esc_attr( number_format( $rating, 1 ) ) );
	?>">
	<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
		<svg class="stars__icon<?php echo $i <= $filled ? ' stars__icon--filled' : ''; ?>"
			width="12" height="12"
			viewBox="0 0 16 16"
			aria-hidden="true"
			focusable="false"
			xmlns="http://www.w3.org/2000/svg">
			<path d="M8 1l2 5 5 .5-4 3.5 1.5 5L8 12l-4.5 3L5 10 1 6.5 6 6z"/>
		</svg>
	<?php endfor; ?>
</span>
