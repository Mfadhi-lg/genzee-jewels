<?php
/**
 * Fallback template.
 * Used when no more specific template matches the current request.
 * This is NOT the homepage — see front-page.php for that.
 *
 * @package genzee-jewels
 */

get_header();
?>

	<div class="container">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<?php the_title(); ?>
							</a>
						</h2>
					</header>

					<div class="entry-content">
						<?php
						if ( is_singular() ) {
							the_content();
						} else {
							the_excerpt();
						}
						?>
					</div>

				</article>

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'No content found.', 'genzee-jewels' ); ?></p>

		<?php endif; ?>

	</div>

<?php
get_footer();
