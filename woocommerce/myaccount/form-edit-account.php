<?php
/**
 * Edit account form.
 *
 * @package genzee-jewels
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="acct-section-head">
	<h2 class="acct-section-head__title"><?php esc_html_e( 'Account details', 'genzee-jewels' ); ?></h2>
	<p class="acct-section-head__sub"><?php esc_html_e( 'Update your name, email address, and password.', 'genzee-jewels' ); ?></p>
</div>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="acct-name-row">
		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_first_name">
				<?php esc_html_e( 'First name', 'genzee-jewels' ); ?>
				<span class="required" aria-hidden="true">*</span>
			</label>
			<input
				type="text"
				class="woocommerce-Input woocommerce-Input--text input-text"
				name="account_first_name"
				id="account_first_name"
				autocomplete="given-name"
				value="<?php echo esc_attr( $user->first_name ); ?>"
			/>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="account_last_name">
				<?php esc_html_e( 'Last name', 'genzee-jewels' ); ?>
				<span class="required" aria-hidden="true">*</span>
			</label>
			<input
				type="text"
				class="woocommerce-Input woocommerce-Input--text input-text"
				name="account_last_name"
				id="account_last_name"
				autocomplete="family-name"
				value="<?php echo esc_attr( $user->last_name ); ?>"
			/>
		</p>
	</div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_display_name">
			<?php esc_html_e( 'Display name', 'genzee-jewels' ); ?>
			<span class="required" aria-hidden="true">*</span>
		</label>
		<input
			type="text"
			class="woocommerce-Input woocommerce-Input--text input-text"
			name="account_display_name"
			id="account_display_name"
			autocomplete="username"
			value="<?php echo esc_attr( $user->display_name ); ?>"
		/>
		<span class="acct-field-hint"><?php esc_html_e( 'This is how your name appears in reviews and your account.', 'genzee-jewels' ); ?></span>
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email">
			<?php esc_html_e( 'Email address', 'genzee-jewels' ); ?>
			<span class="required" aria-hidden="true">*</span>
		</label>
		<input
			type="email"
			class="woocommerce-Input woocommerce-Input--email input-text"
			name="account_email"
			id="account_email"
			autocomplete="email"
			value="<?php echo esc_attr( $user->user_email ); ?>"
		/>
	</p>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<fieldset>
		<legend><?php esc_html_e( 'Password change', 'genzee-jewels' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current">
				<?php esc_html_e( 'Current password', 'genzee-jewels' ); ?>
				<span class="acct-field-hint"><?php esc_html_e( 'Leave blank to keep current password.', 'genzee-jewels' ); ?></span>
			</label>
			<input
				type="password"
				class="woocommerce-Input woocommerce-Input--password input-text"
				name="password_current"
				id="password_current"
				autocomplete="current-password"
			/>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1">
				<?php esc_html_e( 'New password', 'genzee-jewels' ); ?>
				<span class="acct-field-hint"><?php esc_html_e( 'Leave blank to keep current password.', 'genzee-jewels' ); ?></span>
			</label>
			<input
				type="password"
				class="woocommerce-Input woocommerce-Input--password input-text"
				name="password_1"
				id="password_1"
				autocomplete="new-password"
			/>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'genzee-jewels' ); ?></label>
			<input
				type="password"
				class="woocommerce-Input woocommerce-Input--password input-text"
				name="password_2"
				id="password_2"
				autocomplete="new-password"
			/>
		</p>
	</fieldset>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

	<div class="acct-form-actions">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<input type="hidden" name="action" value="save_account_details" />
		<button
			type="submit"
			class="woocommerce-Button button acct-btn acct-btn--primary"
			name="save_account_details"
			value="<?php esc_attr_e( 'Save changes', 'genzee-jewels' ); ?>"
		>
			<?php esc_html_e( 'Save changes', 'genzee-jewels' ); ?>
		</button>
	</div>
</form>
