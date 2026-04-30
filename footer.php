<?php
/**
 * Site footer template.
 * Included at the bottom of every template via get_footer().
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_shop_url    = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '';
$footer_account_url = home_url( '/my-account/' );

if ( empty( $footer_shop_url ) ) {
	$footer_shop_url = home_url( '/shop/' );
}

if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_id' ) ) {
	$footer_account_page_id = wc_get_page_id( 'myaccount' );
	if ( $footer_account_page_id > 0 ) {
		$footer_account_url = get_permalink( $footer_account_page_id );
	}
}

$footer_is_checkout = function_exists( 'is_checkout' ) && is_checkout();

$footer_columns = array(
	array(
		'title' => __( 'Shop', 'genzee-jewels' ),
		'links' => array(
			array( 'label' => __( 'New Drops', 'genzee-jewels' ), 'url' => $footer_shop_url ),
			array( 'label' => __( 'Bestsellers', 'genzee-jewels' ), 'url' => $footer_shop_url ),
			array( 'label' => __( 'Under ₹299', 'genzee-jewels' ), 'url' => $footer_shop_url ),
			array( 'label' => __( 'Sets & Combos', 'genzee-jewels' ), 'url' => home_url( '/product-category/sets/' ) ),
			array( 'label' => __( 'Sale', 'genzee-jewels' ), 'url' => $footer_shop_url ),
		),
	),
	array(
		'title' => __( 'Help', 'genzee-jewels' ),
		'links' => array(
			array( 'label' => __( 'Track Order', 'genzee-jewels' ), 'url' => $footer_account_url ),
			array( 'label' => __( 'Shipping', 'genzee-jewels' ), 'url' => home_url( '/shipping/' ) ),
			array( 'label' => __( 'Returns', 'genzee-jewels' ), 'url' => home_url( '/returns/' ) ),
			array( 'label' => __( 'Size Guide', 'genzee-jewels' ), 'url' => home_url( '/size-guide/' ) ),
			array( 'label' => __( 'Contact', 'genzee-jewels' ), 'url' => home_url( '/contact/' ) ),
		),
	),
	array(
		'title' => __( 'Company', 'genzee-jewels' ),
		'links' => array(
			array( 'label' => __( 'About', 'genzee-jewels' ), 'url' => home_url( '/about/' ) ),
			array( 'label' => __( 'Careers', 'genzee-jewels' ), 'url' => home_url( '/careers/' ) ),
			array( 'label' => __( 'Sustainability', 'genzee-jewels' ), 'url' => home_url( '/sustainability/' ) ),
			array( 'label' => __( 'Press', 'genzee-jewels' ), 'url' => home_url( '/press/' ) ),
			array( 'label' => __( 'Wholesale', 'genzee-jewels' ), 'url' => home_url( '/wholesale/' ) ),
		),
	),
);

$footer_socials = array( 'IG', 'TT', 'YT', 'PN' );
$footer_legal_links = array(
	array( 'label' => __( 'Privacy', 'genzee-jewels' ), 'url' => home_url( '/privacy-policy/' ) ),
	array( 'label' => __( 'Terms', 'genzee-jewels' ), 'url' => home_url( '/terms/' ) ),
	array( 'label' => __( 'Cookies', 'genzee-jewels' ), 'url' => home_url( '/cookies/' ) ),
);
?>
</main>

<footer id="site-footer" class="site-footer<?php echo $footer_is_checkout ? ' site-footer--checkout' : ''; ?>" role="contentinfo">
	<div class="container">
		<?php if ( ! $footer_is_checkout ) : ?>
			<div class="site-footer__layout">
			<div class="site-footer__brand-block">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__brand" rel="home">
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?><span class="site-footer__brand-dot" aria-hidden="true">.</span>
				</a>
				<p class="site-footer__brand-copy">
					<?php esc_html_e( 'Jewelry that slaps. Everything under ₹499. Shipped fast, wrapped in pink, designed to feel like a treat.', 'genzee-jewels' ); ?>
				</p>
				<ul class="site-footer__socials" aria-label="<?php esc_attr_e( 'Social channels', 'genzee-jewels' ); ?>">
					<?php foreach ( $footer_socials as $footer_social ) : ?>
					<li class="site-footer__social"><?php echo esc_html( $footer_social ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="site-footer__columns">
				<?php foreach ( $footer_columns as $footer_column ) : ?>
				<div class="site-footer__column">
					<h2 class="site-footer__heading"><?php echo esc_html( $footer_column['title'] ); ?></h2>
					<ul class="site-footer__links">
						<?php foreach ( $footer_column['links'] as $footer_link ) : ?>
						<li>
							<a href="<?php echo esc_url( $footer_link['url'] ); ?>"><?php echo esc_html( $footer_link['label'] ); ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endforeach; ?>
			</div>
			</div>

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
		<?php endif; ?>

		<div class="site-footer__bottom">
			<p class="site-footer__copyright">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
				<?php esc_html_e( 'All rights reserved.', 'genzee-jewels' ); ?>
			</p>
			<?php if ( ! $footer_is_checkout ) : ?>
				<ul class="site-footer__legal">
					<?php foreach ( $footer_legal_links as $footer_legal_link ) : ?>
					<li>
						<a href="<?php echo esc_url( $footer_legal_link['url'] ); ?>">
							<?php echo esc_html( $footer_legal_link['label'] ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/components/cart-drawer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
