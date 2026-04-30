<?php
/**
 * My Account — Login / Registration.
 *
 * Overrides WooCommerce default: woocommerce/myaccount/form-login.php
 *
 * @package     genzee-jewels
 * @version     9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$genzee_register_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="acct-gate<?php echo $genzee_register_enabled ? ' acct-gate--split' : ''; ?>">

	<!-- ── Login panel ───────────────────────────────────────── -->
	<section class="acct-panel acct-panel--login" aria-label="<?php esc_attr_e( 'Log in to your account', 'genzee-jewels' ); ?>">

		<h2 class="acct-panel__heading">
			<?php esc_html_e( 'Welcome back', 'genzee-jewels' ); ?>
		</h2>
		<p class="acct-panel__subcopy">
			<?php esc_html_e( 'Log in to check orders, saved styles, and addresses.', 'genzee-jewels' ); ?>
		</p>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username">
					<?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>
					<span class="required" aria-hidden="true">*</span>
				</label>
				<input
					type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="username"
					id="username"
					autocomplete="username"
					value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" <?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
					required
				/>
			</p>

			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide acct-field-with-link">
				<label for="password">
					<?php esc_html_e( 'Password', 'woocommerce' ); ?>
					<span class="required" aria-hidden="true">*</span>
				</label>
				<a class="acct-lost-password" href="<?php echo esc_url( wc_lostpassword_url() ); ?>">
					<?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?>
				</a>
				<input
					class="woocommerce-Input woocommerce-Input--text input-text"
					type="password"
					name="password"
					id="password"
					autocomplete="current-password"
					required
				/>
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme acct-remember">
				<input
					class="woocommerce-form__input woocommerce-form__input-checkbox"
					name="rememberme"
					type="checkbox"
					id="rememberme"
					value="forever"
				/>
				<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
			</label>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" />

			<button
				type="submit"
				class="woocommerce-button button woocommerce-form-login__submit acct-btn acct-btn--primary"
				name="login"
				value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"
			>
				<?php esc_html_e( 'Log in', 'woocommerce' ); ?> &rarr;
			</button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
	</section>

	<?php if ( $genzee_register_enabled ) : ?>

	<!-- ── Register panel ────────────────────────────────────── -->
	<section class="acct-panel acct-panel--register" aria-label="<?php esc_attr_e( 'Create an account', 'genzee-jewels' ); ?>">

		<h2 class="acct-panel__heading">
			<?php esc_html_e( 'New here?', 'genzee-jewels' ); ?>
		</h2>
		<p class="acct-panel__subcopy">
			<?php esc_html_e( 'Create an account to track orders and save your favourites.', 'genzee-jewels' ); ?>
		</p>

		<ul class="acct-benefits" aria-label="<?php esc_attr_e( 'Account benefits', 'genzee-jewels' ); ?>">
			<li class="acct-benefits__item">
				<span class="acct-benefits__icon" aria-hidden="true">📦</span>
				<span><?php esc_html_e( 'Track your orders', 'genzee-jewels' ); ?></span>
			</li>
			<li class="acct-benefits__item">
				<span class="acct-benefits__icon" aria-hidden="true">🩷</span>
				<span><?php esc_html_e( 'Save favourite styles', 'genzee-jewels' ); ?></span>
			</li>
			<li class="acct-benefits__item">
				<span class="acct-benefits__icon" aria-hidden="true">⚡</span>
				<span><?php esc_html_e( 'Faster checkout', 'genzee-jewels' ); ?></span>
			</li>
			<li class="acct-benefits__item">
				<span class="acct-benefits__icon" aria-hidden="true">📍</span>
				<span><?php esc_html_e( 'Easy address management', 'genzee-jewels' ); ?></span>
			</li>
		</ul>

		<form method="post" class="woocommerce-form woocommerce-form-register register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_username">
					<?php esc_html_e( 'Username', 'woocommerce' ); ?>
					<span class="required" aria-hidden="true">*</span>
				</label>
				<input
					type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="username"
					id="reg_username"
					autocomplete="username"
					value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" <?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
				/>
			</p>
			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email">
					<?php esc_html_e( 'Email address', 'woocommerce' ); ?>
					<span class="required" aria-hidden="true">*</span>
				</label>
				<input
					type="email"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="email"
					id="reg_email"
					autocomplete="email"
					value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" <?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
					required
				/>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_password">
					<?php esc_html_e( 'Password', 'woocommerce' ); ?>
					<span class="required" aria-hidden="true">*</span>
				</label>
				<input
					type="password"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="password"
					id="reg_password"
					autocomplete="new-password"
				/>
			</p>
			<?php else : ?>
			<div class="woocommerce-privacy-policy-text">
				<?php echo wp_kses_post( wc_get_privacy_policy_text( 'registration' ) ); ?>
			</div>
			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

			<button
				type="submit"
				class="woocommerce-button button woocommerce-form-register__submit acct-btn acct-btn--outline"
				name="register"
				value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"
			>
				<?php esc_html_e( 'Create account', 'woocommerce' ); ?> &rarr;
			</button>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
	</section>

	<?php endif; ?>

</div><!-- /.acct-gate -->

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
