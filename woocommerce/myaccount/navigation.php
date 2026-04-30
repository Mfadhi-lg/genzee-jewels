<?php
/**
 * My Account navigation.
 *
 * Overrides WooCommerce default: woocommerce/myaccount/navigation.php
 *
 * The outer <nav> wrapper and before/after hooks live in my-account.php so
 * this file outputs only the <ul> — avoids nesting two <nav> landmarks.
 *
 * @package genzee-jewels
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;
?>

<ul>
	<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
		<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
