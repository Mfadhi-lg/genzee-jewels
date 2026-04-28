<?php
/**
 * Combo / free-shipping nudge banner.
 *
 * Static promotional section — no WooCommerce logic.
 * Encourages basket-building with value bubbles and a direct CTA.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$combo_shop_url = class_exists( 'WooCommerce' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );

$combo_bubbles = array(
	array( 'label' => '₹199',                    'mod' => '' ),
	array( 'label' => '₹299',                    'mod' => '' ),
	array( 'label' => '₹399',                    'mod' => '' ),
	array( 'label' => '3rd FREE',                'mod' => 'combo-bubble--ink' ),
	array( 'label' => 'Free shipping over ₹499', 'mod' => 'combo-bubble--ink' ),
);
?>
<section
	class="section-combo-banner"
	aria-label="<?php esc_attr_e( 'Combo deal and free shipping offer', 'genzee-jewels' ); ?>">

	<div class="combo-banner__wrap">
		<div class="combo-banner__card">

			<!-- Decorative orbs — visual depth only -->
			<div class="combo-banner__orb combo-banner__orb--1" aria-hidden="true"></div>
			<div class="combo-banner__orb combo-banner__orb--2" aria-hidden="true"></div>

			<!-- Left: content ──────────────────────────────── -->
			<div class="combo-banner__content">

				<span class="combo-banner__eyebrow">
					<?php esc_html_e( '💝 COMBO ALERT', 'genzee-jewels' ); ?>
				</span>

				<h2 class="combo-banner__heading">
					<?php esc_html_e( 'Add 2 more =', 'genzee-jewels' ); ?>
					<span class="combo-banner__heading-accent">
						<?php esc_html_e( 'FREE shipping', 'genzee-jewels' ); ?>
					</span>
				</h2>

				<p class="combo-banner__sub">
					<?php esc_html_e( 'Mix any styles you love — hit ₹499 and shipping is on us. The more you grab, the better the deal.', 'genzee-jewels' ); ?>
				</p>

				<ul
					class="combo-banner__bubbles"
					aria-label="<?php esc_attr_e( 'Deal highlights', 'genzee-jewels' ); ?>">

					<?php foreach ( $combo_bubbles as $combo_bubble ) : ?>
						<li class="combo-bubble <?php echo esc_attr( trim( $combo_bubble['mod'] ) ); ?>">
							<?php echo esc_html( $combo_bubble['label'] ); ?>
						</li>
					<?php endforeach; ?>

				</ul><!-- /.combo-banner__bubbles -->

			</div><!-- /.combo-banner__content -->

			<!-- Right: CTA ─────────────────────────────────── -->
			<div class="combo-banner__cta-wrap">
				<a
					href="<?php echo esc_url( $combo_shop_url ); ?>"
					class="combo-banner__cta">
					<?php esc_html_e( 'Shop combos', 'genzee-jewels' ); ?>
					<svg
						width="14"
						height="14"
						viewBox="0 0 24 24"
						fill="none"
						stroke="currentColor"
						stroke-width="3"
						aria-hidden="true"
						focusable="false">
						<path d="M 5 12 L 19 12 M 13 6 L 19 12 L 13 18"/>
					</svg>
				</a>
			</div><!-- /.combo-banner__cta-wrap -->

		</div><!-- /.combo-banner__card -->
	</div><!-- /.combo-banner__wrap -->

</section>
