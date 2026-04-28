<?php
/**
 * New Drops section.
 *
 * Queries WooCommerce for the 8 most recently published products ordered by
 * post date DESC. Any unfilled slots fall back to demo cards.
 *
 * Layout: header row (eyebrow + heading + view-all link) followed by a
 * horizontally-scrolling product strip. Scrollbar is hidden via the
 * shared .no-scrollbar utility class from base.css.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ──────────────────────────────────────────────────────────────
   1. Collect real WooCommerce products — 8 newest by publish date
────────────────────────────────────────────────────────────── */

$nd_products = array();
$nd_max      = 8;

if ( class_exists( 'WooCommerce' ) ) {
	$nd_query = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $nd_max,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	if ( $nd_query->have_posts() ) {
		while ( $nd_query->have_posts() ) {
			$nd_query->the_post();
			$nd_p = wc_get_product( get_the_ID() );
			if ( $nd_p ) {
				$nd_products[] = $nd_p;
			}
		}
		wp_reset_postdata();
	}
}

/* ──────────────────────────────────────────────────────────────
   2. Demo fallback — fills any slots not covered by WC (max 8)
────────────────────────────────────────────────────────────── */

$nd_demos = array(
	array(
		'id'      => 'nd-1',
		'name'    => 'Butter Heart Huggies',
		'cat'     => 'Earrings',
		'price'   => 299,
		'mrp'     => 599,
		'rating'  => 4.8,
		'reviews' => 1284,
		'stock'   => 3,
		'tag'     => 'BESTSELLER',
		'color'   => '#FFE14A',
		'link'    => home_url( '/product-category/earrings/' ),
	),
	array(
		'id'      => 'nd-2',
		'name'    => 'Pixie Pearl Drops',
		'cat'     => 'Earrings',
		'price'   => 249,
		'mrp'     => 499,
		'rating'  => 4.7,
		'reviews' => 892,
		'stock'   => 12,
		'tag'     => 'NEW',
		'color'   => '#FAF7F1',
		'link'    => home_url( '/product-category/earrings/' ),
	),
	array(
		'id'      => 'nd-3',
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
		'id'      => 'nd-4',
		'name'    => 'Y2K Chrome Hoops',
		'cat'     => 'Earrings',
		'price'   => 349,
		'mrp'     => 699,
		'rating'  => 4.6,
		'reviews' => 567,
		'stock'   => 8,
		'tag'     => null,
		'color'   => '#C9C9D6',
		'link'    => home_url( '/product-category/earrings/' ),
	),
	array(
		'id'      => 'nd-5',
		'name'    => 'Starburst Choker',
		'cat'     => 'Necklaces',
		'price'   => 449,
		'mrp'     => 899,
		'rating'  => 4.8,
		'reviews' => 734,
		'stock'   => 2,
		'tag'     => 'LOW STOCK',
		'color'   => '#C9B6FF',
		'link'    => home_url( '/product-category/necklaces/' ),
	),
	array(
		'id'      => 'nd-6',
		'name'    => 'Layered Dainty Chain',
		'cat'     => 'Necklaces',
		'price'   => 399,
		'mrp'     => 799,
		'rating'  => 4.9,
		'reviews' => 1456,
		'stock'   => 15,
		'tag'     => 'BESTSELLER',
		'color'   => '#FFE14A',
		'link'    => home_url( '/product-category/necklaces/' ),
	),
	array(
		'id'      => 'nd-7',
		'name'    => 'Pearl Baby Lariat',
		'cat'     => 'Necklaces',
		'price'   => 299,
		'mrp'     => 599,
		'rating'  => 4.7,
		'reviews' => 523,
		'stock'   => 9,
		'tag'     => null,
		'color'   => '#F4EFE6',
		'link'    => home_url( '/product-category/necklaces/' ),
	),
	array(
		'id'      => 'nd-8',
		'name'    => 'Bubble Ring Set (3pc)',
		'cat'     => 'Rings',
		'price'   => 249,
		'mrp'     => 599,
		'rating'  => 4.8,
		'reviews' => 1122,
		'stock'   => 6,
		'tag'     => 'SET',
		'color'   => '#FF7A3D',
		'link'    => home_url( '/product-category/rings/' ),
	),
);

$nd_demo_needed = max( 0, $nd_max - count( $nd_products ) );
$nd_demos_used  = array_slice( $nd_demos, 0, $nd_demo_needed );

/* ──────────────────────────────────────────────────────────────
   3. Shared shop / new-arrivals URL
────────────────────────────────────────────────────────────── */

$nd_shop_url = class_exists( 'WooCommerce' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );

/* ──────────────────────────────────────────────────────────────
   4. Render
────────────────────────────────────────────────────────────── */
?>
<section
	class="section-new-drops"
	aria-label="<?php esc_attr_e( 'New drops', 'genzee-jewels' ); ?>">

	<!-- ── Section header ─────────────────────────────────────── -->
	<div class="new-drops-header-wrap">
		<div class="new-drops-header">

			<div class="new-drops-heading-block">
				<span class="new-drops-eyebrow">
					<?php esc_html_e( '✨ Fresh this week', 'genzee-jewels' ); ?>
				</span>
				<h2 class="new-drops-heading">
					<?php esc_html_e( 'New drops', 'genzee-jewels' ); ?>
				</h2>
			</div><!-- /.new-drops-heading-block -->

			<a
				href="<?php echo esc_url( $nd_shop_url ); ?>"
				class="new-drops-view-all">
				<?php esc_html_e( 'View all new arrivals →', 'genzee-jewels' ); ?>
			</a>

		</div><!-- /.new-drops-header -->
	</div><!-- /.new-drops-header-wrap -->

	<!-- ── Horizontal scroll track ────────────────────────────── -->
	<div class="new-drops-track-wrap">
		<ul
			class="new-drops-track no-scrollbar"
			aria-label="<?php esc_attr_e( 'New product drops', 'genzee-jewels' ); ?>">

			<?php foreach ( $nd_products as $nd_wc_product ) : ?>
				<li class="new-drops-item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'product' => $nd_wc_product )
					);
					?>
				</li>
			<?php endforeach; ?>

			<?php foreach ( $nd_demos_used as $nd_demo ) : ?>
				<li class="new-drops-item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'demo' => $nd_demo )
					);
					?>
				</li>
			<?php endforeach; ?>

		</ul><!-- /.new-drops-track -->
	</div><!-- /.new-drops-track-wrap -->

</section>
