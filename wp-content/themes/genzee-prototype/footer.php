</main>
<footer class="site-footer"><div class="site-footer__inner">
	<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'menu menu--footer', 'fallback_cb' => false ) ); ?>
</div></footer>
<?php wp_footer(); ?>
</body>
</html>
