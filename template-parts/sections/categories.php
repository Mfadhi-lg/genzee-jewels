<?php
/**
 * Shop by Category section.
 *
 * Tries WooCommerce product_cat taxonomy first; enriches fallback
 * static data with live counts and permalinks when terms exist.
 * Falls back to the static array when WooCommerce is inactive or
 * no matching terms are found.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Helper: inline SVG art for each category ─────────────────
if ( ! function_exists( 'genzee_category_svg' ) ) {
	/**
	 * Returns a hardcoded decorative SVG string for the given slug.
	 * This is a static theme asset — not user input — so it is safe
	 * to echo directly without wp_kses.
	 *
	 * @param string $slug Category slug.
	 * @return string SVG markup.
	 */
	function genzee_category_svg( string $slug ): string {
		$m = '#E6B84D'; // gold
		$d = '#B8872C'; // gold deep

		$svgs = [
			'earrings' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<g transform="translate(70,110)">
					<path d="M0-5 C-13-22-34-8 0 18 C34-8 13-22 0-5 Z" fill="' . $m . '" stroke="' . $d . '" stroke-width="1.5"/>
					<circle cx="-5" cy="-9" r="2" fill="rgba(255,255,255,0.65)"/>
				</g>
				<g transform="translate(130,110)">
					<path d="M0-5 C-13-22-34-8 0 18 C34-8 13-22 0-5 Z" fill="' . $m . '" stroke="' . $d . '" stroke-width="1.5"/>
					<circle cx="-5" cy="-9" r="2" fill="rgba(255,255,255,0.65)"/>
				</g>
			</svg>',

			'necklaces' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M20 90 Q100 62 180 90" stroke="' . $m . '" stroke-width="2.5" fill="none"/>
				<path d="M35 118 Q100 98 165 118" stroke="' . $m . '" stroke-width="2" fill="none" opacity="0.8"/>
				<circle cx="100" cy="127" r="6" fill="' . $m . '"/>
				<path d="M100 127 L100 146 M94 141 L106 141" stroke="' . $d . '" stroke-width="1.8" stroke-linecap="round" fill="none"/>
			</svg>',

			'rings' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<circle cx="60" cy="110" r="28" fill="none" stroke="' . $m . '" stroke-width="8"/>
				<circle cx="100" cy="110" r="28" fill="none" stroke="#FF2D6F" stroke-width="8"/>
				<circle cx="140" cy="110" r="28" fill="none" stroke="' . $m . '" stroke-width="8"/>
				<circle cx="100" cy="82" r="5" fill="#FF2D6F"/>
			</svg>',

			'bracelets' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<rect x="14" y="96" width="24" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
				<rect x="36" y="96" width="32" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
				<rect x="66" y="96" width="24" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
				<rect x="88" y="96" width="32" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
				<rect x="118" y="96" width="24" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
				<rect x="140" y="96" width="20" height="12" rx="6" fill="none" stroke="' . $m . '" stroke-width="3.5"/>
			</svg>',

			'nose-pins' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<g transform="translate(100,90)">
					<circle cx="0" cy="0" r="5" fill="#FFF" stroke="' . $m . '" stroke-width="1.5"/>
					<path d="M0 0 Q-28 10-24 38 Q-18 52 0 52 Q18 52 24 38 Q28 10 0 0" fill="none" stroke="' . $m . '" stroke-width="1.5"/>
					<circle cx="0" cy="0" r="2.5" fill="#FF2D6F"/>
				</g>
			</svg>',

			'sets' => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<g transform="translate(50,72)">
					<path d="M18 0 A18 18 0 1 1 18 36 A14 14 0 0 0 18 0 Z" fill="' . $m . '"/>
				</g>
				<g transform="translate(138,68)">
					<path d="M0-10 L3-3 L10-3 L4 2 L7 10 L0 6 L-7 10 L-4 2 L-10-3 L-3-3 Z" fill="' . $m . '" transform="scale(1.4)"/>
				</g>
				<g transform="translate(72,150)">
					<path d="M0-8 L2-2 L8-2 L3 2 L5 8 L0 5 L-5 8 L-3 2 L-8-2 L-2-2 Z" fill="' . $m . '" transform="scale(1.2)"/>
				</g>
				<g transform="translate(148,145)">
					<circle cx="0" cy="0" r="12" fill="#FFE14A" stroke="' . $m . '" stroke-width="1.5"/>
				</g>
			</svg>',
		];

		return isset( $svgs[ $slug ] ) ? $svgs[ $slug ] : $svgs['earrings'];
	}
}

// ── Category list ─────────────────────────────────────────────
$categories = [
	[
		'slug'  => 'earrings',
		'name'  => __( 'Earrings', 'genzee-jewels' ),
		'count' => 128,
		'color' => '#FFE14A',
		'tone'  => 'butter',
		'dark'  => false,
		'from'  => '₹179',
		'link'  => home_url( '/product-category/earrings/' ),
	],
	[
		'slug'  => 'necklaces',
		'name'  => __( 'Necklaces', 'genzee-jewels' ),
		'count' => 94,
		'color' => '#C9B6FF',
		'tone'  => 'lilac',
		'dark'  => false,
		'from'  => '₹179',
		'link'  => home_url( '/product-category/necklaces/' ),
	],
	[
		'slug'  => 'rings',
		'name'  => __( 'Rings', 'genzee-jewels' ),
		'count' => 76,
		'color' => '#FF7A3D',
		'tone'  => 'tangerine',
		'dark'  => false,
		'from'  => '₹179',
		'link'  => home_url( '/product-category/rings/' ),
	],
	[
		'slug'  => 'bracelets',
		'name'  => __( 'Bracelets', 'genzee-jewels' ),
		'count' => 62,
		'color' => '#B8F2C9',
		'tone'  => 'mint',
		'dark'  => false,
		'from'  => '₹179',
		'link'  => home_url( '/product-category/bracelets/' ),
	],
	[
		'slug'  => 'nose-pins',
		'name'  => __( 'Nose Pins', 'genzee-jewels' ),
		'count' => 38,
		'color' => '#FF2D6F',
		'tone'  => 'hot',
		'dark'  => false,
		'from'  => '₹149',
		'link'  => home_url( '/product-category/nose-pins/' ),
	],
	[
		'slug'  => 'sets',
		'name'  => __( 'Sets', 'genzee-jewels' ),
		'count' => 45,
		'color' => '#0A0A0A',
		'tone'  => 'ink',
		'dark'  => true,
		'from'  => '₹299',
		'link'  => home_url( '/product-category/sets/' ),
	],
];

// Enrich with WooCommerce data when available
if ( class_exists( 'WooCommerce' ) ) {
	$terms = get_terms(
		[
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		]
	);

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		$term_by_slug = [];
		foreach ( $terms as $term ) {
			if ( 'uncategorized' !== $term->slug ) {
				$term_by_slug[ $term->slug ] = $term;
			}
		}

		foreach ( $categories as &$cat ) {
			if ( isset( $term_by_slug[ $cat['slug'] ] ) ) {
				$term         = $term_by_slug[ $cat['slug'] ];
				$term_link    = get_term_link( $term, 'product_cat' );
				$cat['count'] = $term->count;
				if ( ! is_wp_error( $term_link ) ) {
					$cat['link'] = $term_link;
				}
			}
		}
		unset( $cat );
	}
}

// Shop landing URL
$shop_url = home_url( '/shop/' );
if ( class_exists( 'WooCommerce' ) ) {
	$wc_shop = get_permalink( wc_get_page_id( 'shop' ) );
	if ( $wc_shop ) {
		$shop_url = $wc_shop;
	}
}
?>
<section class="section-categories" aria-labelledby="categories-heading">
	<div class="categories-wrap">

		<div class="categories-header">
			<div>
				<span class="categories-eyebrow">
					<?php esc_html_e( 'SHOP BY CATEGORY', 'genzee-jewels' ); ?>
				</span>
				<h2 class="categories-heading" id="categories-heading">
					<?php esc_html_e( 'Pick your', 'genzee-jewels' ); ?>
					<em><?php esc_html_e( ' poison ', 'genzee-jewels' ); ?></em>→
				</h2>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="categories-view-all">
				<?php esc_html_e( 'View all →', 'genzee-jewels' ); ?>
			</a>
		</div><!-- /.categories-header -->

		<ul class="categories-grid" role="list">
			<?php foreach ( $categories as $cat ) :
				$tile_class = 'cat-tile cat-tile--' . $cat['tone'] . ( ! empty( $cat['dark'] ) ? ' cat-tile--dark' : '' );
			?>
			<li>
				<a href="<?php echo esc_url( $cat['link'] ); ?>"
					class="<?php echo esc_attr( $tile_class ); ?>"
					aria-label="<?php
						printf(
							/* translators: %s: category name */
							esc_attr__( 'Shop %s', 'genzee-jewels' ),
							esc_attr( $cat['name'] )
						);
					?>">

					<div class="cat-tile__art" aria-hidden="true">
						<?php
						// Static theme asset SVG — not user input.
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo genzee_category_svg( $cat['slug'] );
						?>
					</div>

					<div class="cat-tile__body">
						<?php if ( ! empty( $cat['count'] ) ) : ?>
						<span class="cat-tile__count">
							<?php
							printf(
								/* translators: %d: number of items in this category */
								esc_html__( '%d ITEMS', 'genzee-jewels' ),
								absint( $cat['count'] )
							);
							?>
						</span>
						<?php endif; ?>

						<div class="cat-tile__footer">
							<span class="cat-tile__name"><?php echo esc_html( $cat['name'] ); ?></span>
							<span class="cat-tile__from">
								<?php
								printf(
									/* translators: %s: starting price e.g. ₹179 */
									esc_html__( 'From %s', 'genzee-jewels' ),
									esc_html( $cat['from'] )
								);
								?>
							</span>
						</div>
					</div><!-- /.cat-tile__body -->

				</a>
			</li>
			<?php endforeach; ?>
		</ul><!-- /.categories-grid -->

	</div><!-- /.categories-wrap -->
</section>
