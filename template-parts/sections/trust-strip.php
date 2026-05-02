<?php
/**
 * Trust Strip - brand and checkout-safe badges.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$badges = array(
	array(
		'icon'  => '*',
		'title' => __( 'Shipping', 'genzee-jewels' ),
		'sub'   => __( 'calculated at checkout', 'genzee-jewels' ),
	),
	array(
		'icon'  => '*',
		'title' => __( 'Giftable Packaging', 'genzee-jewels' ),
		'sub'   => __( 'cute presentation focus', 'genzee-jewels' ),
	),
	array(
		'icon'  => '*',
		'title' => __( 'Store Policies', 'genzee-jewels' ),
		'sub'   => __( 'shown on policy pages', 'genzee-jewels' ),
	),
	array(
		'icon'  => '*',
		'title' => __( 'Payment Options', 'genzee-jewels' ),
		'sub'   => __( 'shown at checkout', 'genzee-jewels' ),
	),
);
?>
<section class="section-trust" aria-label="<?php esc_attr_e( 'Why shop with Genzee', 'genzee-jewels' ); ?>">
	<div class="trust-wrap">
		<ul class="trust-grid" role="list">
			<?php foreach ( $badges as $badge ) : ?>
			<li class="trust-badge">
				<span class="trust-badge__icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
				<div class="trust-badge__text">
					<strong class="trust-badge__title"><?php echo esc_html( $badge['title'] ); ?></strong>
					<span class="trust-badge__sub"><?php echo esc_html( $badge['sub'] ); ?></span>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
