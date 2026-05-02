<?php
/**
 * My Account - Addresses.
 *
 * @package genzee-jewels
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="acct-section-head">
	<h2 class="acct-section-head__title"><?php esc_html_e( 'Addresses', 'genzee-jewels' ); ?></h2>
	<p class="acct-section-head__sub"><?php esc_html_e( 'Your saved delivery and billing addresses are shown below.', 'genzee-jewels' ); ?></p>
</div>

<?php if ( ! $has_addresses ) : ?>
	<div class="acct-empty-state">
		<span class="acct-empty-state__icon" aria-hidden="true">📍</span>
		<p class="acct-empty-state__title"><?php esc_html_e( 'No addresses saved yet', 'genzee-jewels' ); ?></p>
		<p class="acct-empty-state__copy"><?php esc_html_e( 'Add a billing or shipping address to speed up checkout.', 'genzee-jewels' ); ?></p>
	</div>
<?php endif; ?>

<div class="woocommerce-Addresses addresses">
	<?php foreach ( $load_address as $name => $address_info ) : ?>
		<?php
		$formatted = wc_get_account_formatted_address( $name );
		$is_empty  = 0 === absint( preg_match_all( '/\w+/', wp_strip_all_tags( $formatted ) ) );
		$edit_url  = wc_get_endpoint_url( 'edit-address', $name, wc_get_page_permalink( 'myaccount' ) );
		?>
		<div class="woocommerce-Address<?php echo $is_empty ? ' woocommerce-Address--empty' : ''; ?>">
			<div class="woocommerce-Address-title">
				<h2><?php echo esc_html( $address_info['title'] ); ?></h2>
				<a
					href="<?php echo esc_url( $edit_url ); ?>"
					class="button acct-addr-edit"
					aria-label="<?php
						printf(
							/* translators: %s: address type label. */
							esc_attr__( 'Edit %s', 'genzee-jewels' ),
							esc_attr( $address_info['title'] )
						);
					?>"
				>
					<?php esc_html_e( 'Edit', 'genzee-jewels' ); ?>
				</a>
			</div>

			<address>
				<?php if ( ! $is_empty ) : ?>
					<?php echo wp_kses_post( $formatted ); ?>
				<?php else : ?>
					<span class="acct-addr-empty-label">
						<?php esc_html_e( 'No address saved for this type.', 'genzee-jewels' ); ?>
					</span>
				<?php endif; ?>
			</address>
		</div>
	<?php endforeach; ?>
</div>
