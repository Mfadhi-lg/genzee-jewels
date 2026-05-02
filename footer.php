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

$footer_get_page_url = static function ( $slug ) {
	$page = get_page_by_path( $slug );
	return $page instanceof WP_Post ? get_permalink( $page ) : '';
};

$footer_about_url   = $footer_get_page_url( 'about' );
$footer_contact_url = $footer_get_page_url( 'contact' );
$footer_terms_url   = $footer_get_page_url( 'terms' );
$footer_privacy_url = get_privacy_policy_url();

$footer_columns = array(
	array(
		'title' => __( 'Shop', 'genzee-jewels' ),
		'links' => array(
			array( 'label' => __( 'New Drops', 'genzee-jewels' ), 'url' => add_query_arg( 'orderby', 'date', $footer_shop_url ) ),
			array( 'label' => __( 'All Jewellery', 'genzee-jewels' ), 'url' => $footer_shop_url ),
		),
	),
	array(
		'title' => __( 'Help', 'genzee-jewels' ),
		'links' => array_filter(
			array(
				array( 'label' => __( 'Track Order', 'genzee-jewels' ), 'url' => $footer_account_url ),
				array( 'label' => __( 'My Account', 'genzee-jewels' ), 'url' => $footer_account_url ),
				$footer_contact_url ? array( 'label' => __( 'Contact', 'genzee-jewels' ), 'url' => $footer_contact_url ) : null,
			)
		),
	),
	array(
		'title' => __( 'Company', 'genzee-jewels' ),
		'links' => array_filter(
			array(
				$footer_about_url ? array( 'label' => __( 'About', 'genzee-jewels' ), 'url' => $footer_about_url ) : null,
			)
		),
	),
);

$footer_socials = array( 'IG', 'TT', 'YT', 'PN' );
$footer_legal_links = array_filter(
	array(
		$footer_privacy_url ? array( 'label' => __( 'Privacy', 'genzee-jewels' ), 'url' => $footer_privacy_url ) : null,
		$footer_terms_url ? array( 'label' => __( 'Terms', 'genzee-jewels' ), 'url' => $footer_terms_url ) : null,
	)
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
					<?php esc_html_e( 'Trendy fashion jewellery with playful styling, cute packaging, and prices made for easy outfit upgrades.', 'genzee-jewels' ); ?>
				</p>
				<ul class="site-footer__socials" aria-label="<?php esc_attr_e( 'Social channels', 'genzee-jewels' ); ?>">
					<?php foreach ( $footer_socials as $footer_social ) : ?>
					<li class="site-footer__social"><?php echo esc_html( $footer_social ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="site-footer__columns">
				<?php foreach ( $footer_columns as $footer_column ) : ?>
					<?php if ( empty( $footer_column['links'] ) ) { continue; } ?>
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
				<?php if ( ! empty( $footer_legal_links ) ) : ?>
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
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/components/cart-drawer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
