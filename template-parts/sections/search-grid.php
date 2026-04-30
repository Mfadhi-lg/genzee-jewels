<?php
/**
 * Search results grid.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args              = isset( $args ) && is_array( $args ) ? $args : array();
$is_product_search = ! empty( $args['is_product_search'] );
$search_query      = isset( $args['query'] ) ? (string) $args['query'] : get_search_query( false );
$shop_url          = home_url( '/shop/' );
$rendered          = 0;
$has_results       = have_posts();
global $wp_query;
$result_count      = isset( $wp_query->found_posts ) ? absint( $wp_query->found_posts ) : 0;

$suggestions = array(
	'Gold hoop earrings',
	'Gold huggies',
	'Chunky gold hoops',
	'Mini gold hoops',
);
?>

<section class="search-results" aria-label="<?php esc_attr_e( 'Search results', 'genzee-jewels' ); ?>">
	<div class="container search-results__inner search-results__layout">
		<aside class="search-results__sidebar" aria-label="<?php esc_attr_e( 'Search suggestions', 'genzee-jewels' ); ?>">
			<div class="search-suggestions">
				<p class="search-suggestions__eyebrow">
					<?php esc_html_e( 'Did you mean', 'genzee-jewels' ); ?>
				</p>
				<ul class="search-suggestions__list">
					<?php foreach ( $suggestions as $suggestion ) : ?>
						<?php
						$suggestion_url = add_query_arg(
							array(
								's'         => $suggestion,
								'post_type' => 'product',
							),
							home_url( '/' )
						);
						?>
						<li class="search-suggestions__item">
							<a class="search-suggestions__link" href="<?php echo esc_url( $suggestion_url ); ?>">
								<span class="search-suggestions__arrow" aria-hidden="true">&nearr;</span>
								<?php echo esc_html( $suggestion ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

		</aside>

		<div class="search-results__content">
			<div class="search-toolbar" role="group" aria-label="<?php esc_attr_e( 'Search result tools', 'genzee-jewels' ); ?>">
				<div class="search-toolbar__main">
					<?php if ( '' !== $search_query ) : ?>
						<p class="search-toolbar__meta">
							<?php
							printf(
								/* translators: 1: result count, 2: search term */
								esc_html( _n( '%1$s result matching "%2$s"', '%1$s results matching "%2$s"', $result_count, 'genzee-jewels' ) ),
								esc_html( number_format_i18n( $result_count ) ),
								esc_html( $search_query )
							);
							?>
						</p>
					<?php else : ?>
						<p class="search-toolbar__meta">
							<?php
							printf(
								/* translators: %s: result count */
								esc_html( _n( '%s result', '%s results', $result_count, 'genzee-jewels' ) ),
								esc_html( number_format_i18n( $result_count ) )
							);
							?>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $has_results ) : ?>
				<?php if ( $is_product_search ) : ?>
					<ul class="search-results__grid" aria-label="<?php esc_attr_e( 'Matching products', 'genzee-jewels' ); ?>">
						<?php while ( have_posts() ) : ?>
							<?php
							the_post();

							$search_product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

							if ( ! $search_product ) {
								continue;
							}

							$rendered++;
							?>
							<li class="search-results__item">
								<?php
								get_template_part(
									'template-parts/components/product-card',
									null,
									array(
										'product' => $search_product,
									)
								);
								?>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php else : ?>
					<div class="search-results__posts">
						<?php while ( have_posts() ) : ?>
							<?php
							the_post();
							$rendered++;
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-results__post' ); ?>>
								<h2 class="search-results__post-title">
									<a href="<?php echo esc_url( get_permalink() ); ?>">
										<?php echo esc_html( get_the_title() ); ?>
									</a>
								</h2>
								<div class="search-results__post-excerpt">
									<?php echo wp_kses_post( get_the_excerpt() ); ?>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>

				<?php if ( $rendered > 0 ) : ?>
					<nav class="search-results__pagination" aria-label="<?php esc_attr_e( 'Search pagination', 'genzee-jewels' ); ?>">
						<?php
						the_posts_pagination(
							array(
								'mid_size'           => 2,
								'prev_text'          => esc_html__( 'Previous', 'genzee-jewels' ),
								'next_text'          => esc_html__( 'Next', 'genzee-jewels' ),
								'screen_reader_text' => esc_html__( 'Search results pagination', 'genzee-jewels' ),
							)
						);
						?>
					</nav>
				<?php endif; ?>
			<?php endif; ?>

			<div class="search-ig-card">
				<p class="search-ig-card__eyebrow">
					<?php esc_html_e( "Can't find it?", 'genzee-jewels' ); ?>
				</p>
				<h2 class="search-ig-card__title">
					<?php esc_html_e( "DM us on IG, we'll source it", 'genzee-jewels' ); ?> <span aria-hidden="true">&#10024;</span>
				</h2>
				<a class="search-ig-card__button" href="<?php echo esc_url( 'https://www.instagram.com/genzee.jewels/' ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( '@GENZEE.JEWELS', 'genzee-jewels' ); ?> <span aria-hidden="true">&rarr;</span>
				</a>
			</div>

			<?php if ( ! $has_results || 0 === $rendered ) : ?>
				<div class="search-empty">
					<p class="search-empty__eyebrow">
						<?php esc_html_e( 'No matches', 'genzee-jewels' ); ?>
					</p>
					<h2 class="search-empty__title">
						<?php esc_html_e( 'No styles found.', 'genzee-jewels' ); ?>
					</h2>
					<p class="search-empty__copy">
						<?php esc_html_e( 'Try a simpler search or shop new drops.', 'genzee-jewels' ); ?>
					</p>
					<a class="search-empty__button" href="<?php echo esc_url( $shop_url ); ?>">
						<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
