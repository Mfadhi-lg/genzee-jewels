<?php
/**
 * My Account page shell — editorial layout.
 *
 * @package genzee-jewels
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

$genzee_account_user  = wp_get_current_user();
$genzee_first_name    = '';
$genzee_avatar_letter = 'G';

if ( $genzee_account_user instanceof WP_User && $genzee_account_user->exists() ) {
	$genzee_first_name    = $genzee_account_user->first_name ?: $genzee_account_user->display_name;
	$genzee_avatar_letter = mb_strtoupper( mb_substr( $genzee_first_name ?: 'G', 0, 1 ) );
}
?>

<div class="acct-shell<?php echo is_user_logged_in() ? ' acct-shell--in' : ' acct-shell--out'; ?>">

	<?php wc_print_notices(); ?>

	<?php if ( is_user_logged_in() ) : ?>

		<div class="acct-layout">

			<!-- ── Sidebar ───────────────────────────────── -->
			<aside class="acct-sidebar" aria-label="<?php esc_attr_e( 'Account navigation', 'genzee-jewels' ); ?>">

				<div class="acct-sidebar__greeting">
					<p class="acct-sidebar__label"><?php esc_html_e( 'jewel keeper.', 'genzee-jewels' ); ?></p>
					<p class="acct-sidebar__name">
						<?php echo esc_html( $genzee_first_name ?: __( 'Genzee bestie', 'genzee-jewels' ) ); ?>,
					</p>
				</div>

				<?php do_action( 'woocommerce_before_account_navigation' ); ?>

				<nav class="woocommerce-MyAccount-navigation acct-nav" aria-label="<?php esc_attr_e( 'Account pages', 'genzee-jewels' ); ?>">
					<?php do_action( 'woocommerce_account_navigation' ); ?>
				</nav>

				<?php do_action( 'woocommerce_after_account_navigation' ); ?>

				<div class="acct-sidebar__care">
					<p class="acct-sidebar__care-eyebrow"><?php esc_html_e( 'Need help?', 'genzee-jewels' ); ?></p>
					<p class="acct-sidebar__care-title"><?php esc_html_e( 'Care + Policies', 'genzee-jewels' ); ?></p>
					<p class="acct-sidebar__care-copy"><?php esc_html_e( 'Order and policy details appear in your account and store policy pages.', 'genzee-jewels' ); ?></p>
				</div>

			</aside>

			<!-- ── Main content ──────────────────────────── -->
			<section class="acct-main" id="acct-main-content" aria-label="<?php esc_attr_e( 'Account content', 'genzee-jewels' ); ?>">
				<div class="woocommerce-MyAccount-content">
					<?php do_action( 'woocommerce_account_content' ); ?>
				</div>
			</section>

		</div>

	<?php else : ?>

		<!-- ── Logged-out gate ───────────────────────────── -->
		<div class="acct-gate-wrap">
			<div class="acct-gate-hero">
				<p class="acct-gate-hero__eyebrow"><?php esc_html_e( 'My Account', 'genzee-jewels' ); ?></p>
				<h1 class="acct-gate-hero__title"><?php esc_html_e( 'Sign in to your Genzee world', 'genzee-jewels' ); ?></h1>
				<p class="acct-gate-hero__sub"><?php esc_html_e( 'Track orders, save your favourites, and manage your details — all in one place.', 'genzee-jewels' ); ?></p>
			</div>
			<div class="acct-gate-card">
				<div class="woocommerce-MyAccount-content">
					<?php do_action( 'woocommerce_account_content' ); ?>
				</div>
			</div>
		</div>

	<?php endif; ?>

</div>
