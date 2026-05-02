<?php
/**
 * Reusable in-grid promo tile.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/promo-tile', null, [
 *       'type' => 'flash'|'freebie'|'shop-look',
 *   ] );
 *
 * The calling template must supply the <li> wrapper so the tile
 * can be dropped into any list-based grid.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args      = isset( $args ) && is_array( $args ) ? $args : array();
$tile_type = isset( $args['type'] ) ? (string) $args['type'] : '';

if ( '' === $tile_type ) {
	return;
}

switch ( $tile_type ) {

	/* ── 1. Flash offer ────────────────────────────────────── */
	case 'flash':
		?>
		<div class="promo-tile promo-tile--flash">
			<div class="promo-tile__flash-inner">

				<div class="promo-tile__flash-top">
					<span class="promo-tile__badge promo-tile__badge--hot">
						<?php esc_html_e( 'STYLE EDIT', 'genzee-jewels' ); ?>
					</span>

					<h3 class="promo-tile__flash-heading">
						<?php esc_html_e( 'Style', 'genzee-jewels' ); ?><br>
						<?php esc_html_e( 'edit', 'genzee-jewels' ); ?>
					</h3>

					<p class="promo-tile__flash-copy">
						<?php esc_html_e( 'Browse pieces that are easy to mix into your everyday stack.', 'genzee-jewels' ); ?>
					</p>
				</div>

			</div>
		</div>
		<?php
		break;

	/* ── 2. Freebie nudge ──────────────────────────────────── */
	case 'freebie':
		?>
		<div class="promo-tile promo-tile--freebie">
			<p class="promo-tile__freebie-eyebrow">
				<?php esc_html_e( 'STYLE NOTE', 'genzee-jewels' ); ?>
			</p>
			<h3 class="promo-tile__freebie-heading">
				<?php esc_html_e( 'Small pieces can change the whole stack.', 'genzee-jewels' ); ?>
			</h3>
		</div>
		<?php
		break;

	/* ── 3. Shop the look ──────────────────────────────────── */
	case 'shop-look':
		$shop_look_url = class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' )
			? wc_get_page_permalink( 'shop' )
			: home_url( '/shop/' );
		?>
		<a class="promo-tile promo-tile--shop-look"
			href="<?php echo esc_url( $shop_look_url ); ?>"
			aria-label="<?php esc_attr_e( 'Shop styling ideas', 'genzee-jewels' ); ?>">

			<span class="promo-tile__shop-look-art" aria-hidden="true">
				<svg viewBox="0 0 600 800" focusable="false" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
					<defs>
						<linearGradient id="promo-shop-look-bg" x1="0" y1="0" x2="0" y2="1">
							<stop offset="0%" stop-color="#C9B6FF"/>
							<stop offset="100%" stop-color="#8B6FD4"/>
						</linearGradient>
						<linearGradient id="promo-shop-look-light" x1="0" y1="0" x2="1" y2="1">
							<stop offset="0%" stop-color="rgba(255,255,255,0.38)"/>
							<stop offset="100%" stop-color="rgba(255,255,255,0)"/>
						</linearGradient>
					</defs>
					<rect width="600" height="800" fill="url(#promo-shop-look-bg)"/>
					<rect width="600" height="800" fill="url(#promo-shop-look-light)"/>
					<circle cx="120" cy="120" r="96" fill="rgba(255,255,255,0.16)"/>
					<circle cx="520" cy="250" r="64" fill="rgba(255,225,74,0.18)"/>
					<g>
						<ellipse cx="300" cy="320" rx="148" ry="182" fill="#3B241A"/>
						<rect x="260" y="382" width="80" height="118" rx="18" fill="#E8B499"/>
						<path d="M98 494C190 456 410 456 502 494V800H98Z" fill="#FFFFFF"/>
						<ellipse cx="300" cy="342" rx="108" ry="142" fill="#E8B499"/>
						<path d="M188 250C242 188 292 206 300 224C308 206 358 188 412 250C424 308 398 288 380 260C346 256 318 242 300 268C282 242 254 256 220 260C202 288 176 308 188 250Z" fill="#3B241A"/>
						<circle cx="205" cy="372" r="9" fill="#E6B84D"/>
						<circle cx="395" cy="372" r="9" fill="#E6B84D"/>
						<path d="M218 510C258 538 342 538 382 510" fill="none" stroke="#E6B84D" stroke-width="4" stroke-linecap="round"/>
						<circle cx="300" cy="538" r="8" fill="#FF2D6F"/>
						<circle cx="260" cy="332" r="5" fill="#1A0F08"/>
						<circle cx="340" cy="332" r="5" fill="#1A0F08"/>
						<path d="M270 392C288 406 312 406 330 392" fill="none" stroke="#8F4E3C" stroke-width="4" stroke-linecap="round"/>
					</g>
				</svg>
			</span>

			<div class="promo-tile__shop-look-overlay">
				<span class="promo-tile__badge promo-tile__badge--butter">
					<?php esc_html_e( 'STYLE IDEA', 'genzee-jewels' ); ?>
				</span>
				<div class="promo-tile__shop-look-text">
					<h3 class="promo-tile__shop-look-heading">
						<?php esc_html_e( 'Shop the look', 'genzee-jewels' ); ?>
					</h3>
					<p class="promo-tile__shop-look-sub">
						<?php esc_html_e( 'Easy styling ideas', 'genzee-jewels' ); ?>
					</p>
				</div>
			</div>

		</a>
		<?php
		break;
}
