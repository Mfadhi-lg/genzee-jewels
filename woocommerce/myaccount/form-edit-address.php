<?php
/**
 * Edit address form.
 *
 * @package genzee-jewels
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address )
	? esc_html__( 'Billing address', 'genzee-jewels' )
	: esc_html__( 'Shipping address', 'genzee-jewels' );
?>

<div class="acct-section-head">
	<h2 class="acct-section-head__title"><?php echo esc_html( $page_title ); ?></h2>
	<p class="acct-section-head__sub">
		<a
			href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) . 'edit-address/' ); ?>"
			class="acct-back-link"
			aria-label="<?php esc_attr_e( 'Back to addresses', 'genzee-jewels' ); ?>"
		>&larr; <?php esc_html_e( 'Back to addresses', 'genzee-jewels' ); ?></a>
	</p>
</div>

<form method="post" class="woocommerce-address-fields">
	<?php do_action( 'woocommerce_before_edit_address_form_' . $load_address ); ?>

	<div class="woocommerce-address-fields__field-wrapper">
		<?php
		foreach ( $address as $key => $field ) {
			woocommerce_form_field( $key, $field, get_user_meta( get_current_user_id(), $key, true ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_edit_address_form_' . $load_address ); ?>

	<div class="acct-form-actions">
		<button
			type="submit"
			class="woocommerce-Button button acct-btn acct-btn--primary"
			name="save_address"
			value="<?php esc_attr_e( 'Save address', 'genzee-jewels' ); ?>"
		>
			<?php esc_html_e( 'Save address', 'genzee-jewels' ); ?>
		</button>
		<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
		<input type="hidden" name="action" value="edit_address" />
	</div>
</form>
