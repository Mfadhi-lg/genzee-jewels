<?php
/**
 * Combo styling banner.
 *
 * Static styling section with neutral catalog copy.
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
	array( 'label' => __( 'Everyday', 'genzee-jewels' ), 'mod' => '' ),
	array( 'label' => __( 'Layering', 'genzee-jewels' ), 'mod' => '' ),
	array( 'label' => __( 'Gifting', 'genzee-jewels' ), 'mod' => '' ),
	array( 'label' => __( 'Stacking', 'genzee-jewels' ), 'mod' => 'combo-bubble--ink' ),
	array( 'label' => __( 'New edits', 'genzee-jewels' ), 'mod' => 'combo-bubble--ink' ),
);
?>
<section
	class="section-combo-banner"
	aria-label="<?php esc_attr_e( 'Styling edit', 'genzee-jewels' ); ?>">

	<div class="combo-banner__wrap">
		<div class="combo-banner__card">

			<!-- Decorative orbs — visual depth only -->
			<div class="combo-banner__orb combo-banner__orb--1" aria-hidden="true"></div>
			<div class="combo-banner__orb combo-banner__orb--2" aria-hidden="true"></div>

			<!-- Left: content ──────────────────────────────── -->
			<div class="combo-banner__content">

				<span class="combo-banner__eyebrow">
					<?php esc_html_e( 'STYLE EDIT', 'genzee-jewels' ); ?>
				</span>

				<h2 class="combo-banner__heading">
					<?php esc_html_e( 'Build', 'genzee-jewels' ); ?>
					<span class="combo-banner__heading-accent">
						<?php esc_html_e( 'your stack', 'genzee-jewels' ); ?>
					</span>
				</h2>

				<p class="combo-banner__sub">
					<?php esc_html_e( 'Mix easy pieces from the catalog and review shipping, discounts, and payment options at checkout.', 'genzee-jewels' ); ?>
				</p>

				<ul
					class="combo-banner__bubbles"
					aria-label="<?php esc_attr_e( 'Style highlights', 'genzee-jewels' ); ?>">

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
					<?php esc_html_e( 'Shop styles', 'genzee-jewels' ); ?>
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
