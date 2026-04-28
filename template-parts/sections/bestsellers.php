<?php
/**
 * Bestsellers section.
 *
 * Queries WooCommerce for up to 4 best-selling published products ordered
 * by total_sales meta (highest first). Any empty slots are filled with
 * demo cards that match brand design.
 *
 * Category tab row is visual-only in Phase 7. No AJAX filtering.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ──────────────────────────────────────────────────────────────
   1. Collect real WooCommerce bestsellers
────────────────────────────────────────────────────────────── */

$bs_products = array();
$bs_max      = 4;

if ( class_exists( 'WooCommerce' ) ) {
	$bs_query = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $bs_max,
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'total_sales',
		'order'          => 'DESC',
	) );

	if ( $bs_query->have_posts() ) {
		while ( $bs_query->have_posts() ) {
			$bs_query->the_post();
			$bs_p = wc_get_product( get_the_ID() );
			if ( $bs_p ) {
				$bs_products[] = $bs_p;
			}
		}
		wp_reset_postdata();
	}
}

/* ──────────────────────────────────────────────────────────────
   2. Demo fallback — fills any card slots not covered by WC
────────────────────────────────────────────────────────────── */

$bs_demos = array(
	array(
		'id'      => 'bs-1',
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
		'id'      => 'bs-2',
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
		'id'      => 'bs-3',
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
		'id'      => 'bs-4',
		'name'    => 'Paperclip Stack Bracelet',
		'cat'     => 'Bracelets',
		'price'   => 329,
		'mrp'     => 649,
		'rating'  => 4.8,
		'reviews' => 678,
		'stock'   => 7,
		'tag'     => null,
		'color'   => '#C9B6FF',
		'link'    => home_url( '/product-category/bracelets/' ),
	),
);

$bs_demo_needed = max( 0, $bs_max - count( $bs_products ) );
$bs_demos_used  = array_slice( $bs_demos, 0, $bs_demo_needed );

/* ──────────────────────────────────────────────────────────────
   3. Category tabs — visual only in this phase
────────────────────────────────────────────────────────────── */

$bs_tabs = array(
	array( 'label' => 'All',       'slug' => 'all' ),
	array( 'label' => 'Earrings',  'slug' => 'earrings' ),
	array( 'label' => 'Necklaces', 'slug' => 'necklaces' ),
	array( 'label' => 'Bracelets', 'slug' => 'bracelets' ),
	array( 'label' => 'Sets',      'slug' => 'sets' ),
);

$bs_shop_url = class_exists( 'WooCommerce' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );

?>
<section
	class="section-bestsellers"
	aria-label="<?php esc_attr_e( 'Bestsellers', 'genzee-jewels' ); ?>">

	<div class="bestsellers-wrap">

		<!-- ── Section header ─────────────────────────────────── -->
		<div class="bestsellers-header">

			<div class="bestsellers-heading-block">
				<span class="bestsellers-eyebrow">
					<?php esc_html_e( '🔥 BESTSELLERS', 'genzee-jewels' ); ?>
				</span>
				<h2 class="bestsellers-heading">
					<?php esc_html_e( "Everyone's buying", 'genzee-jewels' ); ?><em><?php esc_html_e( ' these.', 'genzee-jewels' ); ?></em>
				</h2>
				<p class="bestsellers-sub">
					<?php esc_html_e( 'Budget-friendly jewellery people are actually adding to cart.', 'genzee-jewels' ); ?>
				</p>
			</div><!-- /.bestsellers-heading-block -->

			<!-- Tab filter row — visual only, no AJAX in Phase 7 -->
			<div
				class="bestsellers-tabs"
				role="group"
				aria-label="<?php esc_attr_e( 'Filter by category', 'genzee-jewels' ); ?>">

				<?php foreach ( $bs_tabs as $bs_idx => $bs_tab ) : ?>
					<button
						type="button"
						class="bestsellers-tab<?php echo 0 === $bs_idx ? ' bestsellers-tab--active' : ''; ?>"
						aria-pressed="<?php echo 0 === $bs_idx ? 'true' : 'false'; ?>">
						<?php echo esc_html( $bs_tab['label'] ); ?>
					</button>
				<?php endforeach; ?>

			</div><!-- /.bestsellers-tabs -->

		</div><!-- /.bestsellers-header -->

		<!-- ── Product grid ───────────────────────────────────── -->
		<ul
			class="bestsellers-grid"
			aria-label="<?php esc_attr_e( 'Bestselling products', 'genzee-jewels' ); ?>">

			<?php foreach ( $bs_products as $bs_wc_product ) : ?>
				<li class="bestsellers-grid__item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'product' => $bs_wc_product )
					);
					?>
				</li>
			<?php endforeach; ?>

			<?php foreach ( $bs_demos_used as $bs_demo ) : ?>
				<li class="bestsellers-grid__item">
					<?php
					get_template_part(
						'template-parts/components/product-card',
						null,
						array( 'demo' => $bs_demo )
					);
					?>
				</li>
			<?php endforeach; ?>

		</ul><!-- /.bestsellers-grid -->

		<!-- ── Footer link ────────────────────────────────────── -->
		<div class="bestsellers-footer">
			<a
				href="<?php echo esc_url( $bs_shop_url ); ?>"
				class="bestsellers-footer__link">
				<?php esc_html_e( 'View all bestsellers →', 'genzee-jewels' ); ?>
			</a>
		</div><!-- /.bestsellers-footer -->

	</div><!-- /.bestsellers-wrap -->

</section>
