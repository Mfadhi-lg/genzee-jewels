<?php
/**
 * Archive filters section.
 *
 * Renders honest, functional filters backed by real WooCommerce data.
 * Metal and colour filter groups are only shown when real attribute taxonomy
 * terms exist and have products.  All active filters can be deselected by
 * clicking the same option again.  Rating uses WooCommerce's native
 * rating_filter query var (WC_Query handles the meta query).
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_filter_mode = isset( $args['mode'] ) ? sanitize_key( $args['mode'] ) : 'desktop';
if ( ! in_array( $archive_filter_mode, array( 'desktop', 'mobile' ), true ) ) {
	$archive_filter_mode = 'desktop';
}
$archive_filter_heading_suffix = 'mobile' === $archive_filter_mode ? 'mobile' : 'desktop';

// Strip dirty params that must never appear in filter links.
$archive_filter_base_url = remove_query_arg(
	array( 'paged', 'add-to-cart', 'wc-ajax', 'nonce', 'remove_item', 'update_cart' )
);

// Generic URL builder: removes $remove_args from the clean base, then appends $add_args.
$archive_filter_build_url = static function ( $add_args = array(), $remove_args = array() ) use ( $archive_filter_base_url ) {
	$url = remove_query_arg( $remove_args, $archive_filter_base_url );
	return empty( $add_args ) ? $url : add_query_arg( $add_args, $url );
};

// ── Active filter values ────────────────────────────────────────────────────
// phpcs:disable WordPress.Security.NonceVerification.Recommended
$archive_filter_selected_min    = isset( $_GET['min_price'] ) ? absint( wp_unslash( $_GET['min_price'] ) ) : 0;
$archive_filter_selected_max    = isset( $_GET['max_price'] ) ? absint( wp_unslash( $_GET['max_price'] ) ) : 0;
$archive_filter_selected_rating = isset( $_GET['rating_filter'] ) ? absint( wp_unslash( $_GET['rating_filter'] ) ) : 0;
$archive_filter_selected_cat    = isset( $_GET['filter_category'] ) ? sanitize_title( wp_unslash( $_GET['filter_category'] ) ) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

// ── Metal taxonomy detection ────────────────────────────────────────────────
$archive_filter_metal_tax   = '';
$archive_filter_metal_param = 'filter_metal';
$archive_filter_metal_terms = array();

if ( taxonomy_exists( 'pa_metal' ) ) {
	$_af_metal_raw = get_terms(
		array(
			'taxonomy'   => 'pa_metal',
			'hide_empty' => true,
			'orderby'    => 'name',
		)
	);
	if ( ! is_wp_error( $_af_metal_raw ) && ! empty( $_af_metal_raw ) ) {
		$archive_filter_metal_tax   = 'pa_metal';
		$archive_filter_metal_terms = $_af_metal_raw;
	}
	unset( $_af_metal_raw );
}

// phpcs:disable WordPress.Security.NonceVerification.Recommended
$archive_filter_selected_metal = ( '' !== $archive_filter_metal_tax && isset( $_GET[ $archive_filter_metal_param ] ) )
	? sanitize_title( wp_unslash( $_GET[ $archive_filter_metal_param ] ) )
	: '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

// ── Colour taxonomy detection (pa_color first, then pa_colour) ──────────────
$archive_filter_color_tax   = '';
$archive_filter_color_param = 'filter_color';
$archive_filter_color_terms = array();

foreach ( array( 'pa_color', 'pa_colour' ) as $_af_try_color ) {
	if ( taxonomy_exists( $_af_try_color ) ) {
		$_af_color_raw = get_terms(
			array(
				'taxonomy'   => $_af_try_color,
				'hide_empty' => true,
				'orderby'    => 'name',
			)
		);
		if ( ! is_wp_error( $_af_color_raw ) && ! empty( $_af_color_raw ) ) {
			$archive_filter_color_tax   = $_af_try_color;
			$archive_filter_color_param = 'filter_' . str_replace( 'pa_', '', $_af_try_color );
			$archive_filter_color_terms = $_af_color_raw;
			break;
		}
		unset( $_af_color_raw );
	}
}
unset( $_af_try_color );

// phpcs:disable WordPress.Security.NonceVerification.Recommended
$archive_filter_selected_color = ( '' !== $archive_filter_color_tax && isset( $_GET[ $archive_filter_color_param ] ) )
	? sanitize_title( wp_unslash( $_GET[ $archive_filter_color_param ] ) )
	: '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

// ── Price display state ─────────────────────────────────────────────────────
$archive_filter_display_min = ( $archive_filter_selected_max > 0 || $archive_filter_selected_min > 0 )
	? $archive_filter_selected_min
	: 0;
$archive_filter_display_max = $archive_filter_selected_max > 0 ? $archive_filter_selected_max : 499;

// ── Static options ──────────────────────────────────────────────────────────
$archive_filter_price_options = array(
	array(
		'label' => __( '₹0–₹199', 'genzee-jewels' ),
		'min'   => 0,
		'max'   => 199,
	),
	array(
		'label' => __( '₹0–₹299', 'genzee-jewels' ),
		'min'   => 0,
		'max'   => 299,
	),
	array(
		'label' => __( '₹300–₹499', 'genzee-jewels' ),
		'min'   => 300,
		'max'   => 499,
	),
);

$archive_filter_rating_options = array(
	array(
		'value' => 4,
		'label' => __( '4★ & up', 'genzee-jewels' ),
	),
	array(
		'value' => 3,
		'label' => __( '3★ & up', 'genzee-jewels' ),
	),
);

// ── Clear-all and active-state summary ──────────────────────────────────────
$archive_filter_any_active = $archive_filter_selected_max > 0
	|| $archive_filter_selected_min > 0
	|| '' !== $archive_filter_selected_metal
	|| '' !== $archive_filter_selected_color
	|| $archive_filter_selected_rating > 0;

// Strip all known filter params — covers both pa_color and pa_colour variants.
$archive_filter_clear_url = $archive_filter_build_url(
	array(),
	array( 'min_price', 'max_price', 'filter_metal', 'filter_color', 'filter_colour', 'rating_filter', 'paged' )
);

// ── Persisted filters (carried through category URL clicks) ─────────────────
$archive_filter_persisted = array();
if ( $archive_filter_selected_min > 0 ) {
	$archive_filter_persisted['min_price'] = $archive_filter_selected_min;
}
if ( $archive_filter_selected_max > 0 ) {
	$archive_filter_persisted['max_price'] = $archive_filter_selected_max;
}
if ( '' !== $archive_filter_selected_metal ) {
	$archive_filter_persisted[ $archive_filter_metal_param ] = $archive_filter_selected_metal;
}
if ( '' !== $archive_filter_selected_color ) {
	$archive_filter_persisted[ $archive_filter_color_param ] = $archive_filter_selected_color;
}
if ( $archive_filter_selected_rating > 0 ) {
	$archive_filter_persisted['rating_filter'] = $archive_filter_selected_rating;
}

// ── Category options (real WC terms → fallback stubs) ──────────────────────
$archive_filter_category_options = array();
$archive_filter_default_cat_id   = absint( get_option( 'default_product_cat', 0 ) );
$archive_filter_current_obj      = get_queried_object();
$archive_filter_active_term_id   = 0;

if ( $archive_filter_current_obj instanceof WP_Term && 'product_cat' === $archive_filter_current_obj->taxonomy ) {
	$archive_filter_active_term_id = (int) $archive_filter_current_obj->term_id;
}

if ( taxonomy_exists( 'product_cat' ) ) {
	$_af_cat_terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 5,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'exclude'    => $archive_filter_default_cat_id > 0 ? array( $archive_filter_default_cat_id ) : array(),
		)
	);

	if ( ! is_wp_error( $_af_cat_terms ) && ! empty( $_af_cat_terms ) ) {
		foreach ( $_af_cat_terms as $_af_cat_term ) {
			$_af_term_url = get_term_link( $_af_cat_term );
			if ( is_wp_error( $_af_term_url ) ) {
				continue;
			}
			if ( ! empty( $archive_filter_persisted ) ) {
				$_af_term_url = add_query_arg( $archive_filter_persisted, $_af_term_url );
			}
			$archive_filter_category_options[] = array(
				'label'  => $_af_cat_term->name,
				'slug'   => $_af_cat_term->slug,
				'url'    => $_af_term_url,
				'count'  => (int) $_af_cat_term->count,
				'active' => $archive_filter_active_term_id === (int) $_af_cat_term->term_id
					|| $archive_filter_selected_cat === $_af_cat_term->slug,
			);
		}
		unset( $_af_cat_term, $_af_term_url );
	}
	unset( $_af_cat_terms );
}

if ( empty( $archive_filter_category_options ) ) {
	foreach (
		array(
			array( 'slug' => 'studs',     'label' => __( 'Studs',     'genzee-jewels' ) ),
			array( 'slug' => 'hoops',     'label' => __( 'Hoops',     'genzee-jewels' ) ),
			array( 'slug' => 'drops',     'label' => __( 'Drops',     'genzee-jewels' ) ),
			array( 'slug' => 'huggies',   'label' => __( 'Huggies',   'genzee-jewels' ) ),
			array( 'slug' => 'ear-cuffs', 'label' => __( 'Ear Cuffs', 'genzee-jewels' ) ),
		) as $_af_cat_fb
	) {
		$archive_filter_category_options[] = array(
			'label'  => $_af_cat_fb['label'],
			'slug'   => $_af_cat_fb['slug'],
			'url'    => $archive_filter_build_url(
				array( 'filter_category' => $_af_cat_fb['slug'] ),
				array( 'filter_category', 'paged' )
			),
			'count'  => 0,
			'active' => $archive_filter_selected_cat === $_af_cat_fb['slug'],
		);
	}
	unset( $_af_cat_fb );
}

ob_start();
?>
<div class="archive-filters__stack">

	<?php if ( $archive_filter_any_active ) : ?>
	<section class="archive-filters__card archive-filters__card--active" aria-label="<?php esc_attr_e( 'Active filters', 'genzee-jewels' ); ?>">
		<div class="archive-filters__group-head">
			<h3 class="archive-filters__group-title">
				<?php esc_html_e( 'Active', 'genzee-jewels' ); ?>
			</h3>
			<a class="archive-filters__clear-all" href="<?php echo esc_url( $archive_filter_clear_url ); ?>">
				<?php esc_html_e( 'Clear all', 'genzee-jewels' ); ?>
			</a>
		</div>
		<div class="archive-filters__active-chips">

			<?php if ( $archive_filter_selected_max > 0 || $archive_filter_selected_min > 0 ) : ?>
				<a
					class="archive-filter-active-chip"
					href="<?php echo esc_url( $archive_filter_build_url( array(), array( 'min_price', 'max_price', 'paged' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Remove price filter', 'genzee-jewels' ); ?>"
				>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: min price, 2: max price */
							__( '₹%1$d–₹%2$d ×', 'genzee-jewels' ),
							$archive_filter_display_min,
							$archive_filter_display_max
						)
					);
					?>
				</a>
			<?php endif; ?>

			<?php if ( '' !== $archive_filter_selected_metal && '' !== $archive_filter_metal_tax ) : ?>
				<a
					class="archive-filter-active-chip"
					href="<?php echo esc_url( $archive_filter_build_url( array(), array( $archive_filter_metal_param, 'paged' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Remove metal filter', 'genzee-jewels' ); ?>"
				>
					<?php echo esc_html( $archive_filter_selected_metal ); ?> ×
				</a>
			<?php endif; ?>

			<?php if ( '' !== $archive_filter_selected_color && '' !== $archive_filter_color_tax ) : ?>
				<a
					class="archive-filter-active-chip"
					href="<?php echo esc_url( $archive_filter_build_url( array(), array( $archive_filter_color_param, 'paged' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Remove colour filter', 'genzee-jewels' ); ?>"
				>
					<?php echo esc_html( $archive_filter_selected_color ); ?> ×
				</a>
			<?php endif; ?>

			<?php if ( $archive_filter_selected_rating > 0 ) : ?>
				<a
					class="archive-filter-active-chip"
					href="<?php echo esc_url( $archive_filter_build_url( array(), array( 'rating_filter', 'paged' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Remove rating filter', 'genzee-jewels' ); ?>"
				>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: minimum star rating */
							__( '%d★ & up ×', 'genzee-jewels' ),
							$archive_filter_selected_rating
						)
					);
					?>
				</a>
			<?php endif; ?>

		</div>
	</section>
	<?php endif; ?>

	<section class="archive-filters__card archive-filters__card--price" aria-labelledby="archive-filters-price-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
		<div class="archive-filters__group-head">
			<h3 class="archive-filters__group-title" id="archive-filters-price-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
				<?php esc_html_e( 'Price', 'genzee-jewels' ); ?>
			</h3>
			<p class="archive-filters__group-value">
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: minimum price, 2: maximum price */
						__( '₹%1$d–₹%2$d', 'genzee-jewels' ),
						$archive_filter_display_min,
						$archive_filter_display_max
					)
				);
				?>
			</p>
		</div>

		<div class="archive-filters__price-track" aria-hidden="true">
			<span class="archive-filters__price-track-fill"></span>
			<span class="archive-filters__price-thumb archive-filters__price-thumb--min"></span>
			<span class="archive-filters__price-thumb archive-filters__price-thumb--max"></span>
		</div>

		<div class="archive-filters__pill-grid" role="list" aria-label="<?php esc_attr_e( 'Price filter options', 'genzee-jewels' ); ?>">
			<?php foreach ( $archive_filter_price_options as $archive_filter_price_opt ) : ?>
				<?php
				$archive_filter_price_active = $archive_filter_selected_max === (int) $archive_filter_price_opt['max']
					&& $archive_filter_selected_min === (int) $archive_filter_price_opt['min'];

				// Clicking an active price option removes it (unselect).
				$archive_filter_price_url = $archive_filter_price_active
					? $archive_filter_build_url( array(), array( 'min_price', 'max_price', 'paged' ) )
					: $archive_filter_build_url(
						array(
							'min_price' => (int) $archive_filter_price_opt['min'],
							'max_price' => (int) $archive_filter_price_opt['max'],
						),
						array( 'min_price', 'max_price', 'paged' )
					);
				?>
				<a
					class="archive-filter-pill<?php echo $archive_filter_price_active ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $archive_filter_price_url ); ?>"
					<?php if ( $archive_filter_price_active ) : ?>aria-current="page"<?php endif; ?>
				>
					<?php echo esc_html( $archive_filter_price_opt['label'] ); ?>
					<?php if ( $archive_filter_price_active ) : ?>
						<span class="archive-filter-remove" aria-hidden="true">×</span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<?php if ( ! empty( $archive_filter_category_options ) ) : ?>
	<section class="archive-filters__card" aria-labelledby="archive-filters-category-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
		<h3 class="archive-filters__group-title" id="archive-filters-category-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
			<?php esc_html_e( 'Category', 'genzee-jewels' ); ?>
		</h3>

		<ul class="archive-filters__option-list" aria-label="<?php esc_attr_e( 'Category filter options', 'genzee-jewels' ); ?>">
			<?php foreach ( $archive_filter_category_options as $archive_filter_cat_opt ) : ?>
				<li>
					<a
						class="archive-filter-link<?php echo ! empty( $archive_filter_cat_opt['active'] ) ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $archive_filter_cat_opt['url'] ); ?>"
						<?php if ( ! empty( $archive_filter_cat_opt['active'] ) ) : ?>aria-current="page"<?php endif; ?>
					>
						<span class="archive-filter-link__indicator" aria-hidden="true"></span>
						<span class="archive-filter-link__label"><?php echo esc_html( $archive_filter_cat_opt['label'] ); ?></span>
						<?php if ( ! empty( $archive_filter_cat_opt['count'] ) ) : ?>
							<span class="archive-filter-link__count"><?php echo esc_html( (string) absint( $archive_filter_cat_opt['count'] ) ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $archive_filter_metal_terms ) ) : ?>
	<section class="archive-filters__card" aria-labelledby="archive-filters-metal-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
		<h3 class="archive-filters__group-title" id="archive-filters-metal-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
			<?php esc_html_e( 'Metal', 'genzee-jewels' ); ?>
		</h3>

		<ul class="archive-filters__option-list" aria-label="<?php esc_attr_e( 'Metal filter options', 'genzee-jewels' ); ?>">
			<?php foreach ( $archive_filter_metal_terms as $archive_filter_metal_term ) : ?>
				<?php
				$archive_filter_metal_active = $archive_filter_selected_metal === $archive_filter_metal_term->slug;

				// Clicking an active metal option removes it (unselect).
				$archive_filter_metal_url = $archive_filter_metal_active
					? $archive_filter_build_url( array(), array( $archive_filter_metal_param, 'paged' ) )
					: $archive_filter_build_url(
						array( $archive_filter_metal_param => $archive_filter_metal_term->slug ),
						array( $archive_filter_metal_param, 'paged' )
					);
				?>
				<li>
					<a
						class="archive-filter-link<?php echo $archive_filter_metal_active ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $archive_filter_metal_url ); ?>"
						<?php if ( $archive_filter_metal_active ) : ?>aria-current="page"<?php endif; ?>
					>
						<span class="archive-filter-link__indicator" aria-hidden="true"></span>
						<span class="archive-filter-link__label"><?php echo esc_html( $archive_filter_metal_term->name ); ?></span>
						<?php if ( $archive_filter_metal_term->count > 0 ) : ?>
							<span class="archive-filter-link__count"><?php echo esc_html( (string) absint( $archive_filter_metal_term->count ) ); ?></span>
						<?php endif; ?>
						<?php if ( $archive_filter_metal_active ) : ?>
							<span class="archive-filter-remove" aria-hidden="true">×</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $archive_filter_color_terms ) ) : ?>
	<section class="archive-filters__card" aria-labelledby="archive-filters-color-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
		<h3 class="archive-filters__group-title" id="archive-filters-color-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
			<?php esc_html_e( 'Colour', 'genzee-jewels' ); ?>
		</h3>

		<ul class="archive-filters__option-list" aria-label="<?php esc_attr_e( 'Colour filter options', 'genzee-jewels' ); ?>">
			<?php foreach ( $archive_filter_color_terms as $archive_filter_color_term ) : ?>
				<?php
				$archive_filter_color_active = $archive_filter_selected_color === $archive_filter_color_term->slug;

				// Clicking an active colour option removes it (unselect).
				$archive_filter_color_url = $archive_filter_color_active
					? $archive_filter_build_url( array(), array( $archive_filter_color_param, 'paged' ) )
					: $archive_filter_build_url(
						array( $archive_filter_color_param => $archive_filter_color_term->slug ),
						array( $archive_filter_color_param, 'paged' )
					);
				?>
				<li>
					<a
						class="archive-filter-link<?php echo $archive_filter_color_active ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $archive_filter_color_url ); ?>"
						<?php if ( $archive_filter_color_active ) : ?>aria-current="page"<?php endif; ?>
					>
						<span class="archive-filter-link__indicator" aria-hidden="true"></span>
						<span class="archive-filter-link__label"><?php echo esc_html( $archive_filter_color_term->name ); ?></span>
						<?php if ( $archive_filter_color_term->count > 0 ) : ?>
							<span class="archive-filter-link__count"><?php echo esc_html( (string) absint( $archive_filter_color_term->count ) ); ?></span>
						<?php endif; ?>
						<?php if ( $archive_filter_color_active ) : ?>
							<span class="archive-filter-remove" aria-hidden="true">×</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<section class="archive-filters__card" aria-labelledby="archive-filters-rating-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
		<h3 class="archive-filters__group-title" id="archive-filters-rating-title-<?php echo esc_attr( $archive_filter_heading_suffix ); ?>">
			<?php esc_html_e( 'Rating', 'genzee-jewels' ); ?>
		</h3>

		<ul class="archive-filters__option-list" aria-label="<?php esc_attr_e( 'Rating filter options', 'genzee-jewels' ); ?>">
			<?php foreach ( $archive_filter_rating_options as $archive_filter_rating_opt ) : ?>
				<?php
				$archive_filter_rating_active = $archive_filter_selected_rating === (int) $archive_filter_rating_opt['value'];

				// Clicking an active rating option removes it (unselect).
				$archive_filter_rating_url = $archive_filter_rating_active
					? $archive_filter_build_url( array(), array( 'rating_filter', 'paged' ) )
					: $archive_filter_build_url(
						array( 'rating_filter' => (int) $archive_filter_rating_opt['value'] ),
						array( 'rating_filter', 'paged' )
					);
				?>
				<li>
					<a
						class="archive-filter-link<?php echo $archive_filter_rating_active ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $archive_filter_rating_url ); ?>"
						<?php if ( $archive_filter_rating_active ) : ?>aria-current="page"<?php endif; ?>
					>
						<span class="archive-filter-link__indicator" aria-hidden="true"></span>
						<span class="archive-filter-link__label"><?php echo esc_html( $archive_filter_rating_opt['label'] ); ?></span>
						<?php if ( $archive_filter_rating_active ) : ?>
							<span class="archive-filter-remove" aria-hidden="true">×</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="archive-filters__promo" aria-label="<?php esc_attr_e( 'Style note', 'genzee-jewels' ); ?>">
		<p class="archive-filters__promo-eyebrow">
			<?php esc_html_e( 'Style note', 'genzee-jewels' ); ?>
		</p>
		<h3 class="archive-filters__promo-title">
			<?php esc_html_e( 'Small pieces can change the whole stack.', 'genzee-jewels' ); ?>
		</h3>
		<p class="archive-filters__promo-copy">
			<?php esc_html_e( 'Use filters to find pieces that fit your everyday styling.', 'genzee-jewels' ); ?>
		</p>
	</section>
</div>
<?php
$archive_filter_cards = ob_get_clean();
?>

<?php if ( 'mobile' === $archive_filter_mode ) : ?>
	<div class="archive-filters-mobile">
		<button
			class="archive-filters-mobile__toggle"
			type="button"
			aria-expanded="false"
			aria-controls="archive-filter-drawer"
		>
			<span class="archive-filters-mobile__toggle-icon" aria-hidden="true"></span>
			<span><?php esc_html_e( 'Filters', 'genzee-jewels' ); ?></span>
			<?php if ( $archive_filter_any_active ) : ?>
				<span class="archive-filters-mobile__active-dot" aria-label="<?php esc_attr_e( 'Active filters', 'genzee-jewels' ); ?>"></span>
			<?php endif; ?>
		</button>

		<div class="archive-filter-drawer" id="archive-filter-drawer" hidden>
			<button
				class="archive-filter-drawer__backdrop"
				type="button"
				data-filter-close
				aria-label="<?php esc_attr_e( 'Close filters', 'genzee-jewels' ); ?>"
			></button>

			<div
				class="archive-filter-drawer__panel"
				role="dialog"
				aria-modal="true"
				aria-labelledby="archive-filter-drawer-title"
			>
				<div class="archive-filter-drawer__header">
					<div>
						<p class="archive-filters__eyebrow">
							<?php esc_html_e( 'Collection', 'genzee-jewels' ); ?>
						</p>
						<h2 class="archive-filter-drawer__title" id="archive-filter-drawer-title">
							<?php esc_html_e( 'Filter products', 'genzee-jewels' ); ?>
						</h2>
					</div>

					<button
						class="archive-filter-drawer__close"
						type="button"
						data-filter-close
						aria-label="<?php esc_attr_e( 'Close filters', 'genzee-jewels' ); ?>"
					>
						<span aria-hidden="true">×</span>
					</button>
				</div>

				<div class="archive-filter-drawer__body">
					<?php echo $archive_filter_cards; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
<?php else : ?>
	<aside class="archive-collection__sidebar archive-filters" aria-label="<?php esc_attr_e( 'Collection filters', 'genzee-jewels' ); ?>">
		<?php echo $archive_filter_cards; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</aside>
<?php endif; ?>
