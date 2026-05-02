<?php
/**
 * Archive header section.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$archive_title = function_exists( 'woocommerce_page_title' )
	? woocommerce_page_title( false )
	: get_the_archive_title();

$archive_title = wp_strip_all_tags( (string) $archive_title );

if ( '' === $archive_title ) {
	$archive_title = esc_html__( 'Shop', 'genzee-jewels' );
}

$archive_count = isset( $wp_query->found_posts ) ? absint( $wp_query->found_posts ) : 0;

if ( $archive_count > 0 ) {
	/* translators: %s: number of products in the archive */
	$archive_count_copy = sprintf(
		_n(
			'%s style to browse',
			'%s styles to browse',
			$archive_count,
			'genzee-jewels'
		),
		number_format_i18n( $archive_count )
	);
} else {
	$archive_count_copy = esc_html__( 'Browse the latest styles', 'genzee-jewels' );
}
?>

<header class="archive-header">
	<div class="archive-header__art" aria-hidden="true">
		<svg viewBox="0 0 280 280" focusable="false" xmlns="http://www.w3.org/2000/svg">
			<rect width="280" height="280" fill="transparent"/>
			<g opacity="0.9">
				<path d="M105 125c-15-25-44-9-5 26 39-35 10-51-5-26z" fill="#E6B84D" stroke="#B8872C" stroke-width="2"/>
				<path d="M175 125c-15-25-44-9-5 26 39-35 10-51-5-26z" fill="#E6B84D" stroke="#B8872C" stroke-width="2"/>
				<circle cx="96" cy="113" r="4" fill="rgba(255,255,255,0.72)"/>
				<circle cx="166" cy="113" r="4" fill="rgba(255,255,255,0.72)"/>
			</g>
		</svg>
	</div>

	<div class="archive-header__inner">
		<div class="archive-header__breadcrumb">
			<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
				<?php
				woocommerce_breadcrumb(
					array(
						'delimiter'   => '<span class="archive-header__breadcrumb-sep" aria-hidden="true">/</span>',
						'wrap_before' => '<nav class="woocommerce-breadcrumb archive-header__breadcrumb-nav" aria-label="' . esc_attr__( 'Breadcrumb', 'genzee-jewels' ) . '">',
						'wrap_after'  => '</nav>',
						'before'      => '<span class="archive-header__breadcrumb-item">',
						'after'       => '</span>',
						'home'        => esc_html__( 'Home', 'genzee-jewels' ),
					)
				);
				?>
			<?php else : ?>
				<nav class="archive-header__breadcrumb-nav" aria-label="<?php esc_attr_e( 'Breadcrumb', 'genzee-jewels' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="archive-header__breadcrumb-item">
						<?php esc_html_e( 'Home', 'genzee-jewels' ); ?>
					</a>
					<span class="archive-header__breadcrumb-sep" aria-hidden="true">/</span>
					<span class="archive-header__breadcrumb-item archive-header__breadcrumb-item--current">
						<?php echo esc_html( $archive_title ); ?>
					</span>
				</nav>
			<?php endif; ?>
		</div>

		<div class="archive-header__content">
			<h1 class="archive-header__title" id="archive-collection-title">
				<?php echo esc_html( $archive_title ); ?><span class="archive-header__title-dot" aria-hidden="true">.</span>
			</h1>

			<div class="archive-header__meta">
				<p class="archive-header__count">
					<?php echo wp_kses_post( $archive_count_copy ); ?>
				</p>

				<span class="archive-header__badge">
					<span aria-hidden="true">*</span>
					<?php esc_html_e( 'Easy to style', 'genzee-jewels' ); ?>
				</span>
			</div>
		</div>
	</div>
</header>
