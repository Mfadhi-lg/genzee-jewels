<?php
/**
 * Section: Newsletter CTA
 *
 * Static shop CTA until a real email provider is connected.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$newsletter_shop_url = class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' )
	? wc_get_page_permalink( 'shop' )
	: home_url( '/shop/' );
?>

<section class="section-newsletter">
	<div class="newsletter-wrap">
		<div class="newsletter-card">
			<div class="newsletter-orb newsletter-orb--1" aria-hidden="true"></div>
			<div class="newsletter-orb newsletter-orb--2" aria-hidden="true"></div>

			<div class="newsletter-content">
				<span class="newsletter-eyebrow"><?php esc_html_e( 'JOIN THE GROUP CHAT', 'genzee-jewels' ); ?></span>

				<h2 class="newsletter-heading">
					<?php esc_html_e( 'Fresh', 'genzee-jewels' ); ?>
					<span class="newsletter-heading__accent"><?php esc_html_e( ' drops', 'genzee-jewels' ); ?></span>
					<?php esc_html_e( ' for your next stack', 'genzee-jewels' ); ?>
				</h2>

				<p class="newsletter-sub">
					<?php esc_html_e( 'Browse the latest Genzee pieces and build an easy everyday edit.', 'genzee-jewels' ); ?>
				</p>

				<div class="newsletter-form" aria-label="<?php esc_attr_e( 'Shop latest products', 'genzee-jewels' ); ?>">
					<a class="newsletter-form__btn" href="<?php echo esc_url( $newsletter_shop_url ); ?>">
						<?php esc_html_e( 'Shop new drops', 'genzee-jewels' ); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false">
							<path d="M5 12L19 12M13 6L19 12L13 18"/>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
