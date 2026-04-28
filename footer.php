<?php
/**
 * Site footer template.
 * Included at the bottom of every template via get_footer().
 *
 * @package genzee-jewels
 */
?>
</main>

<footer id="site-footer" class="site-footer" role="contentinfo">
	<div class="container site-footer__inner">

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav
				class="site-footer__nav"
				aria-label="<?php esc_attr_e( 'Footer navigation', 'genzee-jewels' ); ?>"
			>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'nav-menu nav-menu--footer',
					'depth'          => 1,
				) );
				?>
			</nav>
		<?php endif; ?>

		<p class="site-footer__copyright">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
			<?php esc_html_e( 'All rights reserved.', 'genzee-jewels' ); ?>
		</p>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
