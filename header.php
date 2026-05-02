<?php
/**
 * Site header template.
 * Included at the top of every template via get_header().
 *
 * @package genzee-jewels
 */

// Resolve cart count and URLs safely before HTML output.
$genzee_cart_count  = 0;
$genzee_cart_url    = home_url( '/cart/' );
$genzee_account_url  = home_url( '/my-account/' );
$genzee_shop_url     = home_url( '/shop/' );
$genzee_wishlist_url = function_exists( 'genzee_jewels_get_wishlist_page_url' ) ? genzee_jewels_get_wishlist_page_url() : home_url( '/wishlist/' );
if ( ! $genzee_wishlist_url ) {
	$genzee_wishlist_url = home_url( '/wishlist/' );
}

if ( class_exists( 'WooCommerce' ) ) {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$genzee_cart_count = (int) WC()->cart->get_cart_contents_count();
	}
	if ( function_exists( 'wc_get_cart_url' ) ) {
		$genzee_cart_url = wc_get_cart_url();
	}
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$genzee_shop_url = wc_get_page_permalink( 'shop' );
	}
	if ( function_exists( 'wc_get_page_id' ) ) {
		$account_page_id = wc_get_page_id( 'myaccount' );
		if ( $account_page_id > 0 ) {
			$genzee_account_url = get_permalink( $account_page_id );
		}
	}
}

$genzee_is_checkout = function_exists( 'is_checkout' ) && is_checkout();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">
	<?php esc_html_e( 'Skip to content', 'genzee-jewels' ); ?>
</a>

<header id="site-header" class="site-header" role="banner">

	<!-- Announcement bar -->
	<div
		class="announcement-bar"
		role="region"
		aria-label="<?php esc_attr_e( 'Site announcements', 'genzee-jewels' ); ?>"
	>
		<div class="announcement-bar__track" aria-hidden="true">
			<?php
			$announcements = array(
				__( 'New drops are live', 'genzee-jewels' ),
				__( 'Trendy fashion jewellery for everyday styling', 'genzee-jewels' ),
				__( 'Cute, giftable packaging', 'genzee-jewels' ),
				__( 'Easy checkout with WooCommerce', 'genzee-jewels' ),
			);
			// Render twice for seamless infinite marquee loop.
			for ( $i = 0; $i < 2; $i++ ) :
				foreach ( $announcements as $msg ) :
			?>
				<span class="announcement-bar__item">
					<?php echo wp_kses( $msg, array( 'strong' => array() ) ); ?>
				</span>
				<span class="announcement-bar__sep" aria-hidden="true">&bull;</span>
			<?php
				endforeach;
			endfor;
			?>
		</div>
		<p class="screen-reader-text">
			<?php esc_html_e( 'New drops, trendy fashion jewellery, giftable packaging, and WooCommerce checkout.', 'genzee-jewels' ); ?>
		</p>
	</div>

	<!-- Main header row -->
	<div class="site-header__main">
		<div class="container site-header__inner">

			<!-- Brand / logo -->
			<div class="site-header__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a
						href="<?php echo esc_url( home_url( '/' ) ); ?>"
						class="site-header__logo"
						rel="home"
						aria-label="<?php esc_attr_e( 'Genzee Jewels — Home', 'genzee-jewels' ); ?>"
					>
						<span class="site-header__logo-mark" aria-hidden="true">g</span>
						<span class="site-header__logo-wordmark">
							genzee<span class="site-header__logo-dot" aria-hidden="true">.</span>
						</span>
					</a>
				<?php endif; ?>
			</div>

			<!-- Primary navigation (desktop: always visible; mobile: toggleable) -->
			<nav
				id="primary-navigation"
				class="site-header__nav"
				aria-label="<?php esc_attr_e( 'Primary navigation', 'genzee-jewels' ); ?>"
			>
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-menu nav-menu--primary',
						'fallback_cb'    => false,
					) );
				} else {
					// Fallback nav when no menu is assigned in WP admin.
					$fallback_items = array(
						array( 'label' => 'Home', 'url' => home_url( '/' ) ),
						array( 'label' => 'Shop', 'url' => $genzee_shop_url ),
					);
					$lookbook_page = get_page_by_path( 'lookbook' );
					$about_page    = get_page_by_path( 'about' );
					if ( $lookbook_page instanceof WP_Post ) {
						$fallback_items[] = array( 'label' => 'Lookbook', 'url' => get_permalink( $lookbook_page ) );
					}
					if ( $about_page instanceof WP_Post ) {
						$fallback_items[] = array( 'label' => 'About', 'url' => get_permalink( $about_page ) );
					}
					echo '<ul class="nav-menu nav-menu--primary">';
					foreach ( $fallback_items as $item ) {
						$extra = ! empty( $item['hot'] ) ? ' menu-item--hot' : '';
						printf(
							'<li class="menu-item%s"><a href="%s">%s</a></li>',
							esc_attr( $extra ),
							esc_url( $item['url'] ),
							esc_html( $item['label'] )
						);
					}
					echo '</ul>';
				}
				?>
			</nav>

			<!-- Right icon group -->
			<div class="site-header__actions">

				<?php if ( ! $genzee_is_checkout ) : ?>

				<!-- Search (desktop/tablet only) -->
				<button
					type="button"
					class="site-header__action-btn site-header__search js-search-toggle"
					aria-label="<?php esc_attr_e( 'Open search', 'genzee-jewels' ); ?>"
					aria-expanded="false"
					aria-controls="header-search-panel"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="7"/>
						<path d="M16 16L21 21"/>
					</svg>
				</button>

				<!-- Account (desktop only) -->
				<a
					href="<?php echo esc_url( $genzee_account_url ); ?>"
					class="site-header__action-btn site-header__account"
					aria-label="<?php esc_attr_e( 'My account', 'genzee-jewels' ); ?>"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true" focusable="false">
						<circle cx="12" cy="8" r="4"/>
						<path d="M4 21Q12 15 20 21"/>
					</svg>
				</a>

				<!-- Wishlist (desktop only) -->
				<a
					href="<?php echo esc_url( $genzee_wishlist_url ); ?>"
					class="site-header__action-btn site-header__wishlist"
					aria-label="<?php esc_attr_e( 'Wishlist', 'genzee-jewels' ); ?>"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true" focusable="false">
						<path d="M12 21C6 17 2 13 2 8a5 5 0 0 1 10-2 5 5 0 0 1 10 2c0 5-4 9-10 13z"/>
					</svg>
					<span
						class="site-header__cart-badge site-header__wishlist-badge is-empty"
						aria-hidden="true"
						data-wishlist-count
					>0</span>
				</a>

				<!-- Cart (always visible; JS intercepts click to open drawer, href is no-JS fallback) -->
				<a
					href="<?php echo esc_url( $genzee_cart_url ); ?>"
					class="site-header__action-btn site-header__cart js-cart-toggle"
					aria-label="<?php
						echo esc_attr( sprintf(
							/* translators: %d: number of items in cart */
							_n( 'Cart — %d item', 'Cart — %d items', $genzee_cart_count, 'genzee-jewels' ),
							$genzee_cart_count
						) );
					?>"
					aria-expanded="false"
					aria-controls="cart-drawer"
				>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true" focusable="false">
						<path d="M5 7h14l-1.5 11Q17 20 15 20H9Q7 20 6.5 18Z"/>
						<path d="M9 7Q9 3 12 3Q15 3 15 7"/>
					</svg>
					<span
						class="site-header__cart-badge<?php echo 0 === $genzee_cart_count ? ' is-empty' : ''; ?>"
						aria-hidden="true"
						data-cart-count
					><?php echo esc_html( $genzee_cart_count ); ?></span>
				</a>

				<!-- Mobile hamburger toggle -->
				<button
					type="button"
					class="site-header__menu-toggle"
					aria-controls="primary-navigation"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Open navigation menu', 'genzee-jewels' ); ?>"
				>
					<span class="menu-toggle__bar" aria-hidden="true"></span>
					<span class="menu-toggle__bar" aria-hidden="true"></span>
					<span class="menu-toggle__bar" aria-hidden="true"></span>
				</button>
				<?php else : ?>
				<a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $genzee_cart_url ); ?>" class="site-header__checkout-back">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M19 12H5"></path>
						<path d="M12 19l-7-7 7-7"></path>
					</svg>
					<?php esc_html_e( 'Back to cart', 'genzee-jewels' ); ?>
				</a>
				<?php endif; ?>

			</div>

		</div>
	</div>

	<div id="header-search-panel" class="header-search-panel" hidden>
		<div class="container header-search-panel__inner">
			<form class="header-search-panel__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="header-product-search">
					<?php esc_html_e( 'Search products', 'genzee-jewels' ); ?>
				</label>
				<span class="header-search-panel__icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" focusable="false">
						<circle cx="11" cy="11" r="7"></circle>
						<path d="M16 16L21 21"></path>
					</svg>
				</span>
				<input
					id="header-product-search"
					class="header-search-panel__input"
					type="search"
					name="s"
					placeholder="<?php esc_attr_e( 'Search hoops, chains, sets...', 'genzee-jewels' ); ?>"
					autocomplete="off"
				>
				<input type="hidden" name="post_type" value="product">
				<button class="header-search-panel__submit" type="submit">
					<?php esc_html_e( 'Search', 'genzee-jewels' ); ?>
				</button>
				<button class="header-search-panel__close js-search-close" type="button" aria-label="<?php esc_attr_e( 'Close search', 'genzee-jewels' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</form>
		</div>
	</div>

</header>

<main id="main" class="site-main">
