<?php
/**
 * Impulse Zone — Under ₹299 product grid.
 *
 * Queries WooCommerce for up to 4 published products priced ≤ ₹299,
 * ordered by total sales (best-sellers first).  Any empty slots are
 * filled with demo cards that match the brand design.
 *
 * Product-card markup is fully delegated to
 * template-parts/components/product-card.php — no card markup here.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ──────────────────────────────────────────────────────────────
   1. Collect real WooCommerce products priced ≤ ₹299
────────────────────────────────────────────────────────────── */

$iz_products = array(); // WC_Product instances
$iz_max      = 4;

if ( class_exists( 'WooCommerce' ) ) {
	$iz_query = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $iz_max,
		'meta_query'     => array(
			array(
				'key'     => '_price',
				'value'   => 299,
				'compare' => '<=',
				'type'    => 'NUMERIC',
			),
		),
		'orderby'  => 'meta_value_num',
		'meta_key' => 'total_sales',
		'order'    => 'DESC',
	) );

	if ( $iz_query->have_posts() ) {
		while ( $iz_query->have_posts() ) {
			$iz_query->the_post();
			$iz_p = wc_get_product( get_the_ID() );
			if ( $iz_p ) {
				$iz_products[] = $iz_p;
			}
		}
		wp_reset_postdata();
	}
}

/* ──────────────────────────────────────────────────────────────
   2. Demo fallback data — fills any empty card slots
────────────────────────────────────────────────────────────── */

$iz_demos = array(
	array(
		'id'      => 'iz-1',
		'name'    => 'Cherry Bomb Studs',
		'cat'     => 'Earrings',
		'price'   => 199,
		'mrp'     => 399,
		'rating'  => 4.9,
		'reviews' => 2103,
		'stock'   => 5,
		'tag'     => '🔥 TRENDING',
		'color'   => '#FF2D6F',
		'link'    => home_url( '/product-category/earrings/' ),
	),
	array(
		'id'      => 'iz-2',
		'name'    => 'Pixie Pearl Drops',
		'cat'     => 'Earrings',
		'price'   => 249,
		'mrp'     => 499,
		'rating'  => 4.7,
		'reviews' => 892,
		'stock'   => 12,
		'tag'     => 'NEW',
		'color'   => '#F4EFE6',
		'link'    => home_url( '/product-category/earrings/' ),
	),
	array(
		'id'      => 'iz-3',
		'name'    => 'Evil Eye Signet',
		'cat'     => 'Rings',
		'price'   => 199,
		'mrp'     => 399,
		'rating'  => 4.9,
		'reviews' => 876,
		'stock'   => 4,
		'tag'     => 'LOW STOCK',
		'color'   => '#C9B6FF',
		'link'    => home_url( '/product-category/rings/' ),
	),
	array(
		'id'      => 'iz-4',
		'name'    => 'Daisy Dreamer Ring',
		'cat'     => 'Rings',
		'price'   => 179,
		'mrp'     => 349,
		'rating'  => 4.6,
		'reviews' => 445,
		'stock'   => 20,
		'tag'     => 'NEW',
		'color'   => '#B8F2C9',
		'link'    => home_url( '/product-category/rings/' ),
	),
);

$iz_demo_needed = max( 0, $iz_max - count( $iz_products ) );
$iz_demos_used  = array_slice( $iz_demos, 0, $iz_demo_needed );

/* ──────────────────────────────────────────────────────────────
   3. "View all" URL — filtered shop page when WC is active
────────────────────────────────────────────────────────────── */

$iz_view_all_url = class_exists( 'WooCommerce' )
	? add_query_arg( 'max_price', '299', wc_get_page_permalink( 'shop' ) )
	: home_url( '/shop/' );

?>
<section
	class="section-impulse"
	aria-label="<?php esc_attr_e( 'Under ₹299 — impulse zone', 'genzee-jewels' ); ?>">

	<div class="impulse-wrap">

		<!-- ── Section header ─────────────────────────────────── -->
		<div class="impulse-header">

			<div class="impulse-heading-block">
				<span class="impulse-eyebrow">
					<?php esc_html_e( '⚡ LIMITED DROP', 'genzee-jewels' ); ?>
				</span>
				<h2 class="impulse-heading">
					<?php esc_html_e( 'Under ₹299.', 'genzee-jewels' ); ?><br>
					<em><?php esc_html_e( 'no guilt.', 'genzee-jewels' ); ?></em>
				</h2>
				<p class="impulse-sub">
					<?php esc_html_e( 'Add to cart before your wallet catches on. Trending jewellery under ₹299 — because you deserve it, always.', 'genzee-jewels' ); ?>
				</p>
			</div><!-- /.impulse-heading-block -->

			<!-- Countdown timer — driven by main.js initCountdowns() -->
			<div
				class="impulse-timer"
				aria-label="<?php esc_attr_e( 'Deal ends in', 'genzee-jewels' ); ?>">

				<span class="impulse-timer__label" aria-hidden="true">
					<?php esc_html_e( 'DEAL ENDS IN', 'genzee-jewels' ); ?>
				</span>

				<div class="iz-countdown" data-countdown="14400" aria-live="polite">
					<div class="iz-countdown__unit">
						<span class="iz-countdown__val js-countdown-h">04</span>
						<span class="iz-countdown__lbl" aria-hidden="true">HRS</span>
					</div>
					<span class="iz-countdown__sep" aria-hidden="true">:</span>
					<div class="iz-countdown__unit">
						<span class="iz-countdown__val js-countdown-m">00</span>
						<span class="iz-countdown__lbl" aria-hidden="true">MIN</span>
					</div>
					<span class="iz-countdown__sep" aria-hidden="true">:</span>
					<div class="iz-countdown__unit">
						<span class="iz-countdown__val js-countdown-s">00</span>
						<span class="iz-countdown__lbl" aria-hidden="true">SEC</span>
					</div>
				</div><!-- /.iz-countdown -->

			</div><!-- /.impulse-timer -->

		</div><!-- /.impulse-header -->

		<!-- ── Product grid ───────────────────────────────────── -->
		<ul
			class="impulse-grid"
			aria-label="<?php esc_attr_e( 'Products under ₹299', 'genzee-jewels' ); ?>">

			<?php foreach ( $iz_products as $iz_wc_product ) : ?>
				<li class="impulse-grid__item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'product' => $iz_wc_product )
					);
					?>
				</li>
			<?php endforeach; ?>

			<?php foreach ( $iz_demos_used as $iz_demo ) : ?>
				<li class="impulse-grid__item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'demo' => $iz_demo )
					);
					?>
				</li>
			<?php endforeach; ?>

		</ul><!-- /.impulse-grid -->

		<!-- ── Footer link ────────────────────────────────────── -->
		<div class="impulse-footer">
			<a
				href="<?php echo esc_url( $iz_view_all_url ); ?>"
				class="impulse-footer__link">
				<?php esc_html_e( 'See all Under ₹299 →', 'genzee-jewels' ); ?>
			</a>
		</div><!-- /.impulse-footer -->

	</div><!-- /.impulse-wrap -->

</section>
