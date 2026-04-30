<?php
/**
 * Archive toolbar section.
 *
 * Mirrors the collection design's sticky chip rail with functional links when
 * the matching WooCommerce data exists.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

// phpcs:disable WordPress.Security.NonceVerification.Recommended
$archive_max_price       = isset( $_GET['max_price'] ) ? absint( wp_unslash( $_GET['max_price'] ) ) : 0;
$archive_current_orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

$archive_total    = isset( $wp_query->found_posts ) ? absint( $wp_query->found_posts ) : 0;
$archive_base_url = remove_query_arg(
	array( 'min_price', 'max_price', 'paged', 'add-to-cart', 'wc-ajax', 'nonce', 'remove_item', 'update_cart' )
);

$archive_count_by_price = static function ( $max_price ) {
	if ( ! class_exists( 'WP_Query' ) || ! post_type_exists( 'product' ) ) {
		return 0;
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_price',
					'value'   => (int) $max_price,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				),
			),
		)
	);

	return absint( $query->found_posts );
};

$archive_term_chip = static function ( $label, $taxonomy, $slug, $param = '' ) use ( $archive_base_url ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return array(
			'label' => $label,
			'type'  => 'placeholder',
		);
	}

	$term = get_term_by( 'slug', $slug, $taxonomy );
	if ( ! $term || is_wp_error( $term ) ) {
		return array(
			'label' => $label,
			'type'  => 'placeholder',
		);
	}

	if ( 'product_cat' === $taxonomy ) {
		$url = get_term_link( $term );
		if ( is_wp_error( $url ) ) {
			$url = '';
		}
		$current = get_queried_object();
		$active  = $current instanceof WP_Term && 'product_cat' === $current->taxonomy && (int) $current->term_id === (int) $term->term_id;
	} else {
		$param = '' !== $param ? $param : $taxonomy;
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$current_value = isset( $_GET[ $param ] ) ? sanitize_title( wp_unslash( $_GET[ $param ] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$url    = ( $current_value === $slug ) ? remove_query_arg( $param, $archive_base_url ) : add_query_arg( $param, $slug, $archive_base_url );
		$active = $current_value === $slug;
	}

	return array(
		'label'  => $label,
		'url'    => $url,
		'active' => $active,
		'count'  => absint( $term->count ),
		'type'   => '' !== $url ? 'link' : 'placeholder',
	);
};

$archive_metal_info = function_exists( 'genzee_jewels_get_metal_taxonomy' ) ? genzee_jewels_get_metal_taxonomy() : false;

$archive_chips = array(
	array(
		'label'  => esc_html__( 'All', 'genzee-jewels' ),
		'url'    => $archive_base_url,
		'active' => 0 === $archive_max_price,
		'count'  => $archive_total,
		'type'   => 'link',
	),
	array(
		'label'  => __( 'Under &#8377;199', 'genzee-jewels' ),
		'url'    => add_query_arg( 'max_price', 199, $archive_base_url ),
		'active' => 199 === $archive_max_price,
		'count'  => $archive_count_by_price( 199 ),
		'type'   => 'link',
		'accent' => true,
	),
	array(
		'label'  => __( 'Under &#8377;299', 'genzee-jewels' ),
		'url'    => add_query_arg( 'max_price', 299, $archive_base_url ),
		'active' => 299 === $archive_max_price,
		'count'  => $archive_count_by_price( 299 ),
		'type'   => 'link',
		'accent' => true,
	),
	$archive_term_chip( esc_html__( 'Bestseller', 'genzee-jewels' ), 'product_tag', 'bestseller', 'product_tag' ),
	$archive_term_chip( esc_html__( 'New', 'genzee-jewels' ), 'product_tag', 'new', 'product_tag' ),
	$archive_metal_info ? $archive_term_chip( esc_html__( 'Gold', 'genzee-jewels' ), $archive_metal_info['taxonomy'], 'gold', $archive_metal_info['param'] ) : array( 'label' => esc_html__( 'Gold', 'genzee-jewels' ), 'type' => 'placeholder' ),
	$archive_metal_info ? $archive_term_chip( esc_html__( 'Silver', 'genzee-jewels' ), $archive_metal_info['taxonomy'], 'silver', $archive_metal_info['param'] ) : array( 'label' => esc_html__( 'Silver', 'genzee-jewels' ), 'type' => 'placeholder' ),
	$archive_term_chip( esc_html__( 'Hoops', 'genzee-jewels' ), 'product_cat', 'hoops' ),
	$archive_term_chip( esc_html__( 'Studs', 'genzee-jewels' ), 'product_cat', 'studs' ),
	$archive_term_chip( esc_html__( 'Drops', 'genzee-jewels' ), 'product_cat', 'drops' ),
);

$archive_sort_options = array(
	'menu_order' => esc_html__( 'Trending', 'genzee-jewels' ),
	'popularity' => esc_html__( 'Popular', 'genzee-jewels' ),
	'date'       => esc_html__( 'Newest', 'genzee-jewels' ),
	'price'      => esc_html__( 'Price: Low to High', 'genzee-jewels' ),
	'price-desc' => esc_html__( 'Price: High to Low', 'genzee-jewels' ),
);

$archive_preserved_params = array();
$archive_preserve_keys    = array( 'max_price', 'min_price', 'product_cat', 'product_tag', 'filter_metal', 'filter_color', 'filter_colour', 'rating_filter', 's' );
foreach ( $archive_preserve_keys as $archive_pkey ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET[ $archive_pkey ] ) ) {
		$archive_preserved_params[ $archive_pkey ] = sanitize_text_field( wp_unslash( $_GET[ $archive_pkey ] ) );
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended
}
?>

<div class="archive-toolbar" role="group" aria-label="<?php esc_attr_e( 'Collection tools', 'genzee-jewels' ); ?>">
	<div class="archive-toolbar__chips no-scrollbar" role="list" aria-label="<?php esc_attr_e( 'Filter chips', 'genzee-jewels' ); ?>">
		<?php foreach ( $archive_chips as $archive_chip ) : ?>
			<?php
			$archive_chip_classes = 'archive-chip';

			if ( ! empty( $archive_chip['active'] ) ) {
				$archive_chip_classes .= ' archive-chip--active';
			}

			if ( ! empty( $archive_chip['accent'] ) ) {
				$archive_chip_classes .= ' archive-chip--accent';
			}

			if ( 'placeholder' === $archive_chip['type'] ) {
				$archive_chip_classes .= ' archive-chip--disabled';
			}
			?>

			<?php if ( 'link' === $archive_chip['type'] ) : ?>
				<a
					class="<?php echo esc_attr( $archive_chip_classes ); ?>"
					href="<?php echo esc_url( $archive_chip['url'] ); ?>"
					role="listitem"
					<?php if ( ! empty( $archive_chip['active'] ) ) : ?>aria-current="page"<?php endif; ?>
				>
					<span><?php echo wp_kses_post( $archive_chip['label'] ); ?></span>
					<?php if ( ! empty( $archive_chip['count'] ) ) : ?>
						<span class="archive-chip__count"><?php echo esc_html( number_format_i18n( absint( $archive_chip['count'] ) ) ); ?></span>
					<?php endif; ?>
				</a>
			<?php else : ?>
				<button
					class="<?php echo esc_attr( $archive_chip_classes ); ?>"
					type="button"
					role="listitem"
					disabled
					aria-disabled="true"
				>
					<span><?php echo wp_kses_post( $archive_chip['label'] ); ?></span>
					<?php if ( ! empty( $archive_chip['count'] ) ) : ?>
						<span class="archive-chip__count"><?php echo esc_html( number_format_i18n( absint( $archive_chip['count'] ) ) ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

	<div class="archive-toolbar__controls">
		<div class="archive-toolbar__sort">
			<span class="archive-toolbar__sort-label" aria-hidden="true">
				<?php esc_html_e( 'Sort:', 'genzee-jewels' ); ?>
			</span>

			<?php if ( function_exists( 'woocommerce_catalog_ordering' ) ) : ?>
				<?php woocommerce_catalog_ordering(); ?>
			<?php else : ?>
				<form class="woocommerce-ordering" method="get" action="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>">
					<select
						id="genzee-archive-orderby"
						name="orderby"
						class="orderby"
						aria-label="<?php esc_attr_e( 'Sort products', 'genzee-jewels' ); ?>"
						onchange="this.form.submit()"
					>
						<?php foreach ( $archive_sort_options as $archive_sort_key => $archive_sort_label ) : ?>
							<option
								value="<?php echo esc_attr( $archive_sort_key ); ?>"
								<?php selected( $archive_current_orderby, $archive_sort_key ); ?>
							>
								<?php echo esc_html( $archive_sort_label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php foreach ( $archive_preserved_params as $archive_pkey => $archive_pval ) : ?>
						<input type="hidden" name="<?php echo esc_attr( $archive_pkey ); ?>" value="<?php echo esc_attr( $archive_pval ); ?>">
					<?php endforeach; ?>
					<noscript>
						<button type="submit" class="archive-sort-submit">
							<?php esc_html_e( 'Apply', 'genzee-jewels' ); ?>
						</button>
					</noscript>
				</form>
			<?php endif; ?>
		</div>

		<span class="archive-toolbar__sep" aria-hidden="true"></span>

		<div class="archive-toolbar__view" aria-label="<?php esc_attr_e( 'Grid view', 'genzee-jewels' ); ?>">
			<button class="archive-view-toggle" type="button" data-archive-grid-view="3" aria-pressed="false" aria-label="<?php esc_attr_e( 'Show three columns', 'genzee-jewels' ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
					<rect x="0" y="0" width="4" height="4"/>
					<rect x="6" y="0" width="4" height="4"/>
					<rect x="12" y="0" width="4" height="4"/>
					<rect x="0" y="6" width="4" height="4"/>
					<rect x="6" y="6" width="4" height="4"/>
					<rect x="12" y="6" width="4" height="4"/>
				</svg>
			</button>
			<button class="archive-view-toggle is-active" type="button" data-archive-grid-view="4" aria-pressed="true" aria-label="<?php esc_attr_e( 'Show four columns', 'genzee-jewels' ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
					<rect x="0" y="0" width="3" height="3"/>
					<rect x="4.5" y="0" width="3" height="3"/>
					<rect x="9" y="0" width="3" height="3"/>
					<rect x="13.5" y="0" width="3" height="3"/>
					<rect x="0" y="4.5" width="3" height="3"/>
					<rect x="4.5" y="4.5" width="3" height="3"/>
					<rect x="9" y="4.5" width="3" height="3"/>
					<rect x="13.5" y="4.5" width="3" height="3"/>
				</svg>
			</button>
		</div>
	</div>
</div>
