<?php
/**
 * Hero section — editorial tile + offer grid.
 * Desktop: left tile (1.4fr) + right offer column (1fr).
 * Mobile: stacked single column.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section-hero" aria-label="<?php esc_attr_e( 'Featured collection', 'genzee-jewels' ); ?>">
	<div class="hero-grid">

		<!-- ── Left: editorial hero tile ─────────────────── -->
		<div class="hero-main">

			<!-- Decorative lifestyle art (static SVG placeholder) -->
			<div class="hero-main__art" aria-hidden="true">
				<svg viewBox="0 0 600 480" preserveAspectRatio="xMidYMid slice" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<defs>
						<linearGradient id="hero-art-fade" x1="0" y1="0" x2="0" y2="1">
							<stop offset="0%" stop-color="rgba(255,255,255,0.25)"/>
							<stop offset="100%" stop-color="rgba(0,0,0,0.1)"/>
						</linearGradient>
					</defs>
					<rect width="600" height="480" fill="#D4856A"/>
					<rect width="600" height="480" fill="url(#hero-art-fade)"/>
					<!-- Hair (back) -->
					<ellipse cx="300" cy="230" rx="128" ry="158" fill="#2A1810"/>
					<!-- Neck -->
					<rect x="268" y="292" width="64" height="76" fill="#E8B499"/>
					<!-- Shoulders / shirt -->
					<path d="M80 368 Q300 338 520 368 L520 480 L80 480 Z" fill="#FF2D6F"/>
					<!-- Face -->
					<ellipse cx="300" cy="248" rx="93" ry="112" fill="#E8B499"/>
					<!-- Hair (front) -->
					<path d="M207 168 Q257 118 300 134 Q343 118 393 168 Q407 218 384 198 Q358 166 325 177 Q305 157 300 177 Q295 157 275 177 Q242 166 216 198 Q193 218 207 168 Z" fill="#2A1810"/>
					<!-- Earring hints -->
					<circle cx="214" cy="272" r="7" fill="#E6B84D"/>
					<circle cx="386" cy="272" r="7" fill="#E6B84D"/>
					<!-- Necklace -->
					<path d="M232 390 Q300 426 368 390" stroke="#E6B84D" stroke-width="3" fill="none"/>
					<circle cx="300" cy="422" r="6" fill="#FF2D6F"/>
					<!-- Decorative orbs -->
					<circle cx="524" cy="76" r="94" fill="rgba(255,255,255,0.07)"/>
					<circle cx="56" cy="408" r="62" fill="rgba(255,255,255,0.05)"/>
					<circle cx="480" cy="380" r="42" fill="rgba(255,255,255,0.06)"/>
				</svg>
			</div><!-- /.hero-main__art -->

			<!-- Text-legibility gradient overlay -->
			<div class="hero-main__overlay" aria-hidden="true"></div>

			<!-- Badge pills -->
			<div class="hero-main__pills">
				<span class="hero-pill hero-pill--white">
					<span class="pulse-dot hero-pill__dot" aria-hidden="true"></span>
					<?php esc_html_e( 'LIVE DROP', 'genzee-jewels' ); ?>
				</span>
				<span class="hero-pill hero-pill--butter">
					<?php esc_html_e( "SUMMER '26", 'genzee-jewels' ); ?>
				</span>
			</div>

			<!-- Copy block -->
			<div class="hero-main__copy">
				<p class="hero-main__eyebrow">
					<?php esc_html_e( 'Our new collection, just dropped —', 'genzee-jewels' ); ?>
				</p>
				<h1 class="hero-main__heading">
					<?php esc_html_e( 'Slay', 'genzee-jewels' ); ?><br>
					<?php esc_html_e( 'on', 'genzee-jewels' ); ?>
					<span class="hero-main__heading-accent"><?php esc_html_e( 'a', 'genzee-jewels' ); ?></span><br>
					<?php esc_html_e( 'budget.', 'genzee-jewels' ); ?>
				</h1>
				<div class="hero-main__actions">
					<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"
						class="hero-cta">
						<?php esc_html_e( 'Shop Summer Drop', 'genzee-jewels' ); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="3"
							aria-hidden="true" focusable="false">
							<path d="M5 12h14M13 6l6 6-6 6"/>
						</svg>
					</a>
					<div class="hero-price-note"
						aria-label="<?php esc_attr_e( 'Everything under 499 rupees', 'genzee-jewels' ); ?>">
						<span class="hero-price-note__label">
							<?php esc_html_e( 'EVERYTHING', 'genzee-jewels' ); ?>
						</span>
						<span class="hero-price-note__value">
							<?php esc_html_e( 'Under ₹499', 'genzee-jewels' ); ?>
						</span>
					</div>
				</div>
			</div><!-- /.hero-main__copy -->

		</div><!-- /.hero-main -->

		<!-- ── Right: offer column ──────────────────────── -->
		<div class="hero-aside">

			<!-- Deal of the Day -->
			<div class="hero-deal">
				<div class="hero-deal__blob" aria-hidden="true"></div>
				<div class="hero-deal__label">
					<span class="pulse-dot hero-deal__dot" aria-hidden="true"></span>
					<?php esc_html_e( 'DEAL OF THE DAY', 'genzee-jewels' ); ?>
				</div>
				<p class="hero-deal__heading">
					<?php esc_html_e( 'Flat', 'genzee-jewels' ); ?>
					<span class="hero-deal__accent"><?php esc_html_e( '70% off', 'genzee-jewels' ); ?></span><br>
					<?php esc_html_e( 'on Sets', 'genzee-jewels' ); ?>
				</p>
				<div class="hero-deal__footer">
					<span class="hero-deal__ends-label">
						<?php esc_html_e( 'ENDS IN', 'genzee-jewels' ); ?>
					</span>
					<div class="hero-countdown"
						data-countdown="7845"
						aria-live="polite"
						aria-atomic="true"
						aria-label="<?php esc_attr_e( 'Deal countdown timer', 'genzee-jewels' ); ?>">
						<div class="hero-countdown__unit">
							<span class="hero-countdown__val js-countdown-h">02</span>
						</div>
						<span class="hero-countdown__sep" aria-hidden="true">:</span>
						<div class="hero-countdown__unit">
							<span class="hero-countdown__val js-countdown-m">10</span>
						</div>
						<span class="hero-countdown__sep" aria-hidden="true">:</span>
						<div class="hero-countdown__unit">
							<span class="hero-countdown__val js-countdown-s">45</span>
						</div>
					</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/product-category/sets/' ) ); ?>"
					class="hero-deal__link">
					<?php esc_html_e( 'Shop Sets →', 'genzee-jewels' ); ?>
				</a>
			</div><!-- /.hero-deal -->

			<!-- Category mini-tiles -->
			<div class="hero-tiles">

				<!-- New In — Earrings -->
				<a href="<?php echo esc_url( home_url( '/product-category/earrings/' ) ); ?>"
					class="hero-tile hero-tile--butter"
					aria-label="<?php esc_attr_e( 'Shop New In Earrings — 40+ styles', 'genzee-jewels' ); ?>">
					<div class="hero-tile__art" aria-hidden="true">
						<svg viewBox="0 0 200 200" preserveAspectRatio="xMidYMid slice"
							focusable="false" xmlns="http://www.w3.org/2000/svg">
							<rect width="200" height="200" fill="#FFE14A"/>
							<defs>
								<radialGradient id="earring-vignette" cx="50%" cy="40%" r="65%">
									<stop offset="0%" stop-color="rgba(255,255,255,0.45)"/>
									<stop offset="100%" stop-color="rgba(0,0,0,0.04)"/>
								</radialGradient>
							</defs>
							<rect width="200" height="200" fill="url(#earring-vignette)"/>
							<g transform="translate(72,100)">
								<path d="M0-5 C-13-22-34-8 0 18 C34-8 13-22 0-5 Z" fill="#E6B84D" stroke="#B8872C" stroke-width="1.5"/>
								<circle cx="-6" cy="-10" r="2.5" fill="rgba(255,255,255,0.65)"/>
							</g>
							<g transform="translate(128,100)">
								<path d="M0-5 C-13-22-34-8 0 18 C34-8 13-22 0-5 Z" fill="#E6B84D" stroke="#B8872C" stroke-width="1.5"/>
								<circle cx="-6" cy="-10" r="2.5" fill="rgba(255,255,255,0.65)"/>
							</g>
						</svg>
					</div>
					<span class="hero-tile__tag">
						<?php esc_html_e( 'NEW IN', 'genzee-jewels' ); ?>
					</span>
					<p class="hero-tile__heading">
						<?php esc_html_e( 'Earrings', 'genzee-jewels' ); ?><br>
						<?php esc_html_e( 'dump.', 'genzee-jewels' ); ?>
					</p>
					<span class="hero-tile__cta">
						<?php esc_html_e( 'Shop 40+ styles →', 'genzee-jewels' ); ?>
					</span>
				</a>

				<!-- Trending — Necklaces -->
				<a href="<?php echo esc_url( home_url( '/product-category/necklaces/' ) ); ?>"
					class="hero-tile hero-tile--lilac"
					aria-label="<?php esc_attr_e( 'Shop Trending Dainty Chains from ₹299', 'genzee-jewels' ); ?>">
					<div class="hero-tile__art" aria-hidden="true">
						<svg viewBox="0 0 200 200" preserveAspectRatio="xMidYMid slice"
							focusable="false" xmlns="http://www.w3.org/2000/svg">
							<rect width="200" height="200" fill="#C9B6FF"/>
							<defs>
								<radialGradient id="chain-vignette" cx="50%" cy="40%" r="65%">
									<stop offset="0%" stop-color="rgba(255,255,255,0.45)"/>
									<stop offset="100%" stop-color="rgba(0,0,0,0.04)"/>
								</radialGradient>
							</defs>
							<rect width="200" height="200" fill="url(#chain-vignette)"/>
							<path d="M22 88 Q100 62 178 88" stroke="#E6B84D" stroke-width="2.2" fill="none"/>
							<path d="M32 116 Q100 96 168 116" stroke="#E6B84D" stroke-width="1.8" fill="none" opacity="0.75"/>
							<circle cx="100" cy="123" r="6" fill="#E6B84D"/>
							<path d="M100 123 L100 142 M94 137 L106 137" stroke="#B8872C" stroke-width="1.8" stroke-linecap="round"/>
						</svg>
					</div>
					<span class="hero-tile__tag">
						<?php esc_html_e( 'TRENDING', 'genzee-jewels' ); ?>
					</span>
					<p class="hero-tile__heading">
						<?php esc_html_e( 'Dainty', 'genzee-jewels' ); ?><br>
						<?php esc_html_e( 'chains.', 'genzee-jewels' ); ?>
					</p>
					<span class="hero-tile__cta">
						<?php esc_html_e( 'From ₹299 →', 'genzee-jewels' ); ?>
					</span>
				</a>

			</div><!-- /.hero-tiles -->

		</div><!-- /.hero-aside -->

	</div><!-- /.hero-grid -->
</section>
