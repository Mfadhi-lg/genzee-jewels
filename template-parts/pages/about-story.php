<?php
/**
 * About / Story page content.
 *
 * Static page body inspired by design_files/other-pages.jsx.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_shop_url = home_url( '/shop/' );

if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' ) ) {
	$about_wc_shop_url = wc_get_page_permalink( 'shop' );
	if ( $about_wc_shop_url ) {
		$about_shop_url = $about_wc_shop_url;
	}
}

$about_under_url = add_query_arg( 'max_price', '299', $about_shop_url );
$about_new_url   = add_query_arg( 'orderby', 'date', $about_shop_url );

// Marketing placeholders; replace with real brand numbers when available.
$about_stats = array(
	array(
		'value' => '2.4M+',
		'label' => __( 'Pieces shipped', 'genzee-jewels' ),
		'tone'  => 'butter',
	),
	array(
		'value' => '180K+',
		'label' => __( 'Happy besties', 'genzee-jewels' ),
		'tone'  => 'hot',
	),
	array(
		'value' => '4.8/5',
		'label' => __( 'Average rating', 'genzee-jewels' ),
		'tone'  => 'lilac',
	),
	array(
		'value' => '&lt;&#8377;499',
		'label' => __( 'On everything', 'genzee-jewels' ),
		'tone'  => 'ink',
	),
);

$about_values = array(
	array(
		'number' => '01',
		'title'  => __( 'Quality you can trust', 'genzee-jewels' ),
		'copy'   => __( 'We choose pieces that look polished, feel easy to wear, and are made for everyday styling &mdash; not one-time use.', 'genzee-jewels' ),
		'tone'   => 'butter',
	),
	array(
		'number' => '02',
		'title'  => __( 'Prices that make sense', 'genzee-jewels' ),
		'copy'   => __( 'We keep the range impulse-friendly by cutting unnecessary fluff and focusing on designs people actually want to wear.', 'genzee-jewels' ),
		'tone'   => 'lilac',
	),
	array(
		'number' => '03',
		'title'  => __( 'Packaging worth posting', 'genzee-jewels' ),
		'copy'   => __( 'Every order should feel like a small treat &mdash; cute packaging, neat presentation, and a little bit of main-character energy.', 'genzee-jewels' ),
		'tone'   => 'mint',
	),
);

// Visual placeholders; replace/remove if not real press mentions.
$about_press_mentions = array(
	__( 'VOGUE INDIA', 'genzee-jewels' ),
	__( 'ELLE', 'genzee-jewels' ),
	__( 'YOUR STORY', 'genzee-jewels' ),
	__( 'LBB', 'genzee-jewels' ),
	__( 'COSMOPOLITAN', 'genzee-jewels' ),
	__( 'GRAZIA', 'genzee-jewels' ),
);
?>

<section class="about-hero" aria-labelledby="about-story-title">
	<div class="container about-hero__inner">
		<div class="about-hero__copy">
			<p class="about-eyebrow"><?php esc_html_e( 'THE GENZEE STORY', 'genzee-jewels' ); ?></p>
			<h1 class="about-hero__title" id="about-story-title">
				<span><?php esc_html_e( 'Good jewelry', 'genzee-jewels' ); ?></span>
				<em><?php echo wp_kses_post( __( 'shouldn&rsquo;t cost', 'genzee-jewels' ) ); ?></em>
				<span><?php esc_html_e( 'your rent.', 'genzee-jewels' ); ?></span>
			</h1>
			<p class="about-hero__lead">
				<?php echo wp_kses_post( __( 'We started Genzee because cute jewelry should feel fun, current, and affordable &mdash; without looking like a last-minute street-stall buy.', 'genzee-jewels' ) ); ?>
			</p>
		</div>

		<figure class="about-hero__visual">
			<div class="about-hero__art" aria-hidden="true">
				<svg viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<defs>
						<linearGradient id="about-hero-sheen" x1="0" y1="0" x2="1" y2="1">
							<stop offset="0%" stop-color="#FFDCE6"/>
							<stop offset="52%" stop-color="#F4EFE6"/>
							<stop offset="100%" stop-color="#C9B6FF"/>
						</linearGradient>
						<linearGradient id="about-gold" x1="0" y1="0" x2="1" y2="1">
							<stop offset="0%" stop-color="#FFF3A0"/>
							<stop offset="50%" stop-color="#E6B84D"/>
							<stop offset="100%" stop-color="#B8872C"/>
						</linearGradient>
					</defs>
					<rect width="1200" height="520" fill="url(#about-hero-sheen)"/>
					<path d="M0 404 C186 330 332 426 520 352 C726 270 870 294 1200 208 L1200 520 L0 520 Z" fill="#FF2D6F" opacity="0.2"/>
					<path d="M0 78 C186 120 302 48 456 92 C654 148 796 58 1200 108" fill="none" stroke="#FFFFFF" stroke-width="26" opacity="0.22"/>
					<g transform="translate(162 92)">
						<rect x="0" y="78" width="312" height="258" rx="36" fill="#FFFFFF" opacity="0.92"/>
						<rect x="26" y="108" width="260" height="196" rx="26" fill="#FFE14A"/>
						<path d="M54 218 C104 148 210 148 260 218" fill="none" stroke="#0A0A0A" stroke-width="10" stroke-linecap="round"/>
						<circle cx="102" cy="226" r="32" fill="url(#about-gold)" stroke="#0A0A0A" stroke-width="5"/>
						<circle cx="210" cy="226" r="32" fill="url(#about-gold)" stroke="#0A0A0A" stroke-width="5"/>
						<circle cx="102" cy="226" r="11" fill="#FFFFFF" opacity="0.72"/>
						<circle cx="210" cy="226" r="11" fill="#FFFFFF" opacity="0.72"/>
					</g>
					<g transform="translate(522 54)">
						<ellipse cx="186" cy="192" rx="128" ry="156" fill="#2A1810"/>
						<rect x="150" y="272" width="72" height="84" rx="18" fill="#E8B499"/>
						<path d="M26 352 C124 314 250 314 346 352 L346 466 L26 466 Z" fill="#FF2D6F"/>
						<ellipse cx="186" cy="208" rx="94" ry="118" fill="#E8B499"/>
						<path d="M96 120 C146 70 178 82 186 98 C194 82 226 70 276 120 C286 170 270 156 250 130 C224 128 204 118 186 140 C168 118 148 128 122 130 C102 156 86 170 96 120 Z" fill="#2A1810"/>
						<circle cx="102" cy="230" r="9" fill="url(#about-gold)"/>
						<circle cx="270" cy="230" r="9" fill="url(#about-gold)"/>
						<path d="M122 340 C154 370 218 370 250 340" fill="none" stroke="url(#about-gold)" stroke-width="6" stroke-linecap="round"/>
						<circle cx="186" cy="374" r="9" fill="#FFE14A"/>
						<circle cx="152" cy="194" r="5" fill="#0A0A0A"/>
						<circle cx="220" cy="194" r="5" fill="#0A0A0A"/>
						<path d="M160 250 C174 262 198 262 212 250" fill="none" stroke="#8F4E3C" stroke-width="5" stroke-linecap="round"/>
					</g>
					<g transform="translate(886 104)">
						<rect x="16" y="86" width="236" height="180" rx="30" fill="#0A0A0A"/>
						<rect x="0" y="54" width="268" height="72" rx="28" fill="#FF2D6F"/>
						<path d="M40 126 L228 126" stroke="#FFE14A" stroke-width="10" stroke-linecap="round"/>
						<circle cx="78" cy="190" r="34" fill="#F4EFE6"/>
						<circle cx="190" cy="190" r="34" fill="#F4EFE6"/>
						<path d="M78 156 L78 224 M44 190 L112 190" stroke="#E6B84D" stroke-width="8" stroke-linecap="round"/>
						<path d="M190 164 C162 192 162 212 190 236 C218 212 218 192 190 164 Z" fill="url(#about-gold)"/>
					</g>
					<g fill="#0A0A0A" opacity="0.1">
						<circle cx="92" cy="94" r="16"/>
						<circle cx="1094" cy="72" r="18"/>
						<circle cx="1018" cy="430" r="12"/>
					</g>
				</svg>
			</div>
			<figcaption class="about-hero__meta">
				<?php esc_html_e( 'JAIPUR, INDIA', 'genzee-jewels' ); ?>
				<span aria-hidden="true">&middot;</span>
				<?php esc_html_e( 'EST 2023', 'genzee-jewels' ); ?>
			</figcaption>
		</figure>
	</div>
</section>

<section class="about-stats" aria-label="<?php esc_attr_e( 'Genzee at a glance', 'genzee-jewels' ); ?>">
	<div class="container">
		<div class="about-stats__grid">
			<?php foreach ( $about_stats as $about_stat ) : ?>
				<article class="about-stat about-stat--<?php echo esc_attr( $about_stat['tone'] ); ?>">
					<p class="about-stat__value"><?php echo wp_kses_post( $about_stat['value'] ); ?></p>
					<p class="about-stat__label"><?php echo esc_html( $about_stat['label'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="about-values" aria-labelledby="about-values-title">
	<div class="container about-values__inner">
		<div class="about-section-heading">
			<p class="about-eyebrow"><?php esc_html_e( 'WHAT MATTERS', 'genzee-jewels' ); ?></p>
			<h2 id="about-values-title">
				<?php esc_html_e( 'What we', 'genzee-jewels' ); ?>
				<em><?php esc_html_e( 'actually', 'genzee-jewels' ); ?></em>
				<?php esc_html_e( 'stand for', 'genzee-jewels' ); ?>
			</h2>
		</div>
		<div class="about-values__grid">
			<?php foreach ( $about_values as $about_value ) : ?>
				<article class="about-value-card about-value-card--<?php echo esc_attr( $about_value['tone'] ); ?>">
					<p class="about-value-card__number"><?php echo esc_html( $about_value['number'] ); ?></p>
					<h3><?php echo esc_html( $about_value['title'] ); ?></h3>
					<p><?php echo wp_kses_post( $about_value['copy'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="about-note" aria-labelledby="about-note-title">
	<div class="container about-note__inner">
		<div class="about-note__content">
			<p class="about-eyebrow"><?php esc_html_e( 'BRAND NOTE', 'genzee-jewels' ); ?></p>
			<h2 id="about-note-title">
				<?php esc_html_e( 'Come for the jewelry.', 'genzee-jewels' ); ?><br>
				<?php esc_html_e( 'Stay for the group chat energy.', 'genzee-jewels' ); ?>
			</h2>
			<p>
				<?php echo wp_kses_post( __( 'Genzee is for the girls who want their accessories to keep up with every plan &mdash; college days, coffee runs, birthdays, weddings, reels, and random Tuesday outfit upgrades.', 'genzee-jewels' ) ); ?>
			</p>
			<p class="about-note__signature"><?php echo wp_kses_post( __( '&mdash; Team Genzee', 'genzee-jewels' ) ); ?></p>
			<a class="btn btn--primary about-note__button" href="<?php echo esc_url( $about_shop_url ); ?>">
				<?php esc_html_e( 'SHOP THE DROP', 'genzee-jewels' ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</div>

		<div class="about-note__chat" aria-hidden="true">
			<div class="about-chat-bubble about-chat-bubble--hot"><?php esc_html_e( 'new stack?', 'genzee-jewels' ); ?></div>
			<div class="about-chat-bubble about-chat-bubble--light"><?php esc_html_e( 'coffee run fit', 'genzee-jewels' ); ?></div>
			<div class="about-chat-bubble about-chat-bubble--butter"><?php esc_html_e( 'add hoops', 'genzee-jewels' ); ?></div>
			<div class="about-chat-card">
				<span><?php esc_html_e( 'GENZEE', 'genzee-jewels' ); ?></span>
				<strong><?php esc_html_e( 'main-character energy', 'genzee-jewels' ); ?></strong>
			</div>
		</div>
	</div>
</section>

<section class="about-press" aria-labelledby="about-press-title">
	<div class="container">
		<div class="about-press__panel">
			<h2 id="about-press-title"><?php esc_html_e( 'AS SEEN IN', 'genzee-jewels' ); ?></h2>
			<ul class="about-press__list">
				<?php foreach ( $about_press_mentions as $about_press_mention ) : ?>
					<li><?php echo esc_html( $about_press_mention ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<section class="about-cta" aria-labelledby="about-cta-title">
	<div class="container">
		<div class="about-cta__panel">
			<div class="about-cta__copy">
				<h2 id="about-cta-title"><?php esc_html_e( 'Jewelry that gets the assignment.', 'genzee-jewels' ); ?></h2>
				<p><?php esc_html_e( 'Trendy pieces, affordable prices, and drops made for your everyday outfit rotation.', 'genzee-jewels' ); ?></p>
			</div>
			<div class="about-cta__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( $about_under_url ); ?>">
					<?php esc_html_e( 'Shop Under', 'genzee-jewels' ); ?>
					<span><?php echo wp_kses_post( '&#8377;299' ); ?></span>
					<span aria-hidden="true">&rarr;</span>
				</a>
				<a class="btn btn--outline" href="<?php echo esc_url( $about_new_url ); ?>">
					<?php esc_html_e( 'Shop New Drops', 'genzee-jewels' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</div>
	</div>
</section>
