<?php
/** Homepage hero and campaign marquee. */

get_header();

$shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$new_in_url    = genzee_collection_url( 'New In' );
$bestseller_url = genzee_collection_url( 'Bestsellers' );
$under_299_url  = genzee_collection_url( 'Under ₹299' );
$bracelet_url   = genzee_collection_url( 'Bracelets' );
?>

<section class="genzee-home-hero" aria-labelledby="genzee-hero-title">
	<div class="genzee-home-hero__art" aria-hidden="true">
		<span class="genzee-home-hero__chip">DROP 004 / MORE IS MORE</span>
		<span class="genzee-home-hero__orb genzee-home-hero__orb--one"></span>
		<span class="genzee-home-hero__orb genzee-home-hero__orb--two"></span>
		<i class="genzee-home-sticker genzee-home-sticker--hot">HOT<br>RIGHT<br>NOW</i>
		<i class="genzee-home-sticker genzee-home-sticker--stack">STACK<br>ATTACK</i>
		<i class="genzee-home-sticker genzee-home-sticker--smile">☺</i>
	</div>

	<div class="genzee-home-hero__copy">
		<div>
			<p class="genzee-home-hero__tag">GENZEE / JEWELS FOR THE PLOT</p>
			<h1 id="genzee-hero-title">Tiny jewels.<br><em>Loud decisions.</em></h1>
			<p class="genzee-home-hero__intro">Some outfits need jewellery. Some need intervention. Genzee is the tiny, colourful, stackable part of getting dressed that turns “fine” into “wait, where is that from?”</p>
			<div class="genzee-home-hero__actions">
				<a class="genzee-home-button genzee-home-button--dark" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'I need new stuff', 'genzee-prototype' ); ?></a>
				<a class="genzee-home-button genzee-home-button--hot" href="<?php echo esc_url( $bestseller_url ); ?>"><?php esc_html_e( 'Tell me my mood', 'genzee-prototype' ); ?></a>
			</div>
		</div>

		<div class="genzee-home-hero__facts" aria-label="Shopping highlights">
			<div><b>₹299</b><span>No overthinking required</span></div>
			<div><b>2+</b><span>Looks better together</span></div>
			<div><b>1 MIN</b><span>Outfit emergency solved</span></div>
		</div>
	</div>
</section>

<section class="genzee-home-marquee" aria-label="Featured shopping routes">
	<div class="genzee-home-marquee__track">
		<a href="<?php echo esc_url( $shop_url ); ?>">Tiny but loud</a><a href="<?php echo esc_url( $bracelet_url ); ?>">Wrist snacks</a><a href="<?php echo esc_url( $under_299_url ); ?>">Under ₹299</a><a href="<?php echo esc_url( $shop_url ); ?>">Stack attack</a><a href="<?php echo esc_url( $bestseller_url ); ?>">Gift panic</a><a href="<?php echo esc_url( $new_in_url ); ?>">Cart magnets</a>
		<a href="<?php echo esc_url( $shop_url ); ?>" aria-hidden="true" tabindex="-1">Tiny but loud</a><a href="<?php echo esc_url( $bracelet_url ); ?>" aria-hidden="true" tabindex="-1">Wrist snacks</a><a href="<?php echo esc_url( $under_299_url ); ?>" aria-hidden="true" tabindex="-1">Under ₹299</a><a href="<?php echo esc_url( $shop_url ); ?>" aria-hidden="true" tabindex="-1">Stack attack</a><a href="<?php echo esc_url( $bestseller_url ); ?>" aria-hidden="true" tabindex="-1">Gift panic</a><a href="<?php echo esc_url( $new_in_url ); ?>" aria-hidden="true" tabindex="-1">Cart magnets</a>
	</div>
</section>

<?php
get_footer();
