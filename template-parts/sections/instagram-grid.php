<?php
/**
 * Section: Shop the Gram / Instagram Grid
 *
 * 6 visual placeholder tiles. No Instagram API integration.
 * Colors and SVG art rotate through 3 patterns.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gram_tiles = array(
	array( 'color' => '#FFE14A', 'label' => __( 'Shop 3 items', 'genzee-jewels' ), 'art' => 'hearts' ),
	array( 'color' => '#C9B6FF', 'label' => __( 'Shop 2 items', 'genzee-jewels' ), 'art' => 'hoops' ),
	array( 'color' => '#FF7A3D', 'label' => __( 'Shop 4 items', 'genzee-jewels' ), 'art' => 'stars' ),
	array( 'color' => '#B8F2C9', 'label' => __( 'Shop 1 item', 'genzee-jewels' ),  'art' => 'hearts' ),
	array( 'color' => '#FF2D6F', 'label' => __( 'Shop 3 items', 'genzee-jewels' ), 'art' => 'hoops' ),
	array( 'color' => '#F4EFE6', 'label' => __( 'Shop 2 items', 'genzee-jewels' ), 'art' => 'stars' ),
);

$instagram_shop_url = class_exists( 'WooCommerce' )
	? wc_get_page_permalink( 'shop' )
	: '';

if ( empty( $instagram_shop_url ) ) {
	$instagram_shop_url = home_url( '/shop/' );
}
?>

<section class="section-instagram-grid">
	<div class="instagram-wrap">

		<div class="instagram-header">
			<span class="instagram-eyebrow">📸 <?php esc_html_e( '@GENZEE.JEWELS', 'genzee-jewels' ); ?></span>
			<h2 class="instagram-heading">
				<?php esc_html_e( 'Tag us,', 'genzee-jewels' ); ?>
				<em><?php esc_html_e( ' get reposted.', 'genzee-jewels' ); ?></em>
			</h2>
		</div>

		<ul class="instagram-grid" aria-label="<?php esc_attr_e( 'Instagram style photo grid', 'genzee-jewels' ); ?>">
			<?php foreach ( $gram_tiles as $idx => $tile ) : ?>
			<li>
				<a
					href="<?php echo esc_url( $instagram_shop_url ); ?>"
					class="gram-tile"
					aria-label="<?php echo esc_attr( $tile['label'] ); ?>"
				>
					<svg
						class="gram-tile__art"
						viewBox="0 0 280 280"
						aria-hidden="true"
						focusable="false"
					>
						<rect width="280" height="280" fill="<?php echo esc_attr( $tile['color'] ); ?>"/>
						<defs>
							<radialGradient id="gg-<?php echo esc_attr( $idx ); ?>" cx="50%" cy="40%" r="70%">
								<stop offset="0%" stop-color="rgba(255,255,255,0.35)"/>
								<stop offset="100%" stop-color="rgba(0,0,0,0.06)"/>
							</radialGradient>
						</defs>
						<rect width="280" height="280" fill="url(#gg-<?php echo esc_attr( $idx ); ?>)"/>

						<?php if ( 'hearts' === $tile['art'] ) : ?>
						<g>
							<g transform="translate(105 140)">
								<path d="M 0 -5 C -15 -25,-40 -10, 0 20 C 40 -10,15 -25, 0 -5 Z" fill="#E6B84D" stroke="#B8872C" stroke-width="2"/>
							</g>
							<g transform="translate(175 140)">
								<path d="M 0 -5 C -15 -25,-40 -10, 0 20 C 40 -10,15 -25, 0 -5 Z" fill="#E6B84D" stroke="#B8872C" stroke-width="2"/>
							</g>
						</g>
						<?php elseif ( 'hoops' === $tile['art'] ) : ?>
						<g>
							<circle cx="110" cy="140" r="32" fill="none" stroke="#E6B84D" stroke-width="7"/>
							<circle cx="110" cy="140" r="32" fill="none" stroke="#B8872C" stroke-width="1.5" opacity="0.4"/>
							<circle cx="170" cy="140" r="32" fill="none" stroke="#E6B84D" stroke-width="7"/>
							<circle cx="170" cy="140" r="32" fill="none" stroke="#B8872C" stroke-width="1.5" opacity="0.4"/>
						</g>
						<?php else : ?>
						<g>
							<?php for ( $s = 0; $s < 5; $s++ ) : ?>
							<g transform="translate(<?php echo esc_attr( 70 + $s * 35 ); ?> 145)">
								<path d="M 0 -10 L 3 -3 L 10 -3 L 4 2 L 6 9 L 0 5 L -6 9 L -4 2 L -10 -3 L -3 -3 Z" fill="#E6B84D" stroke="#B8872C" stroke-width="0.5"/>
							</g>
							<?php endfor; ?>
						</g>
						<?php endif; ?>
					</svg>

					<span class="gram-tile__label"><?php echo esc_html( $tile['label'] ); ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
