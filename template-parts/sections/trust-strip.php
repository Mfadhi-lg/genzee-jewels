<?php
/**
 * Trust Strip — four brand promise badges.
 *
 * Matches home.jsx trust section: white card, 4-column badge grid.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$badges = [
	[
		'icon'  => '🚚',
		'title' => __( 'Free Shipping', 'genzee-jewels' ),
		'sub'   => __( 'on orders over ₹499', 'genzee-jewels' ),
	],
	[
		'icon'  => '💝',
		'title' => __( 'Pink Packaging', 'genzee-jewels' ),
		'sub'   => __( 'giftable by default', 'genzee-jewels' ),
	],
	[
		'icon'  => '↩️',
		'title' => __( '7-day Returns', 'genzee-jewels' ),
		'sub'   => __( 'no-questions-asked', 'genzee-jewels' ),
	],
	[
		'icon'  => '💸',
		'title' => __( 'COD Available', 'genzee-jewels' ),
		'sub'   => __( 'pay when it arrives', 'genzee-jewels' ),
	],
];
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
