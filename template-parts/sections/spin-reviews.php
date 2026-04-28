<?php
/**
 * Section: Lucky Spin + Reviews
 *
 * Left: visual spin card (no real coupon logic).
 * Right: 4 hardcoded review cards (can be swapped for WooCommerce reviews later).
 *
 * @package genzee-jewels
 */

$reviews = array(
	array(
		'name'     => 'Priya S.',
		'initial'  => 'P',
		'color'    => '#FFE14A',
		'rating'   => 5,
		'text'     => 'Legit obsessed 😭 the hoops came in the cutest pink pouch, feels like a gift to myself every time I open it.',
		'product'  => 'Y2K Chrome Hoops',
		'verified' => true,
	),
	array(
		'name'     => 'Ananya K.',
		'initial'  => 'A',
		'color'    => '#C9B6FF',
		'rating'   => 5,
		'text'     => "Quality for ₹299??? Shook. Wore them to a wedding and got asked where they're from like 5 times.",
		'product'  => 'Butter Heart Huggies',
		'verified' => true,
	),
	array(
		'name'     => 'Riya M.',
		'initial'  => 'R',
		'color'    => '#FF7A3D',
		'rating'   => 5,
		'text'     => "The packaging alone is chef's kiss. Feels premium for what I paid. Repeat customer for life ✨",
		'product'  => 'Pearl Baby Lariat',
		'verified' => true,
	),
	array(
		'name'     => 'Isha B.',
		'initial'  => 'I',
		'color'    => '#B8F2C9',
		'rating'   => 4,
		'text'     => 'Super dainty and layers beautifully with my other chains. Not the sturdiest but for the price? 10/10.',
		'product'  => 'Layered Dainty Chain',
		'verified' => true,
	),
);
?>

<section class="section-spin-reviews">
	<div class="spin-reviews-wrap">

		<!-- Lucky Spin card (visual only) -->
		<div class="spin-card">
			<span class="spin-card__eyebrow">🎰 <?php esc_html_e( 'LUCKY SPIN', 'genzee-jewels' ); ?></span>
			<h2 class="spin-card__heading">
				<?php esc_html_e( 'Spin for up to', 'genzee-jewels' ); ?><br>
				<span class="spin-card__heading-accent"><?php esc_html_e( '30% OFF', 'genzee-jewels' ); ?></span>
			</h2>
			<p class="spin-card__desc">
				<?php esc_html_e( 'Every new customer gets one free spin. Feeling lucky bestie?', 'genzee-jewels' ); ?>
			</p>

			<div class="spin-card__body">
				<!-- Wheel (decorative) -->
				<div class="spin-wheel" aria-hidden="true">
					<div class="spin-wheel__disc"></div>
					<div class="spin-wheel__center">
						<span><?php esc_html_e( 'SPIN', 'genzee-jewels' ); ?></span>
					</div>
					<div class="spin-wheel__pointer"></div>
				</div>

				<button class="spin-card__cta" type="button">
					<?php esc_html_e( 'Try my luck', 'genzee-jewels' ); ?>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false">
						<path d="M5 12L19 12M13 6L19 12L13 18"/>
					</svg>
				</button>
			</div>
		</div>

		<!-- Reviews panel -->
		<div class="reviews-panel">
			<div class="reviews-panel__header">
				<span class="reviews-panel__eyebrow">💌 <?php esc_html_e( 'REAL REVIEWS', 'genzee-jewels' ); ?></span>
				<h2 class="reviews-panel__heading">
					&ldquo;<?php esc_html_e( 'im obsessed', 'genzee-jewels' ); ?>&rdquo;<em><?php esc_html_e( ' — everyone', 'genzee-jewels' ); ?></em>
				</h2>
			</div>

			<ul class="reviews-grid" aria-label="<?php esc_attr_e( 'Customer reviews', 'genzee-jewels' ); ?>">
				<?php foreach ( $reviews as $review ) : ?>
				<li class="review-card">
					<div class="review-card__top">
						<div
							class="review-card__avatar"
							style="--avatar-bg: <?php echo esc_attr( $review['color'] ); ?>;"
							aria-hidden="true"
						>
							<?php echo esc_html( $review['initial'] ); ?>
						</div>
						<div class="review-card__meta">
							<span class="review-card__name"><?php echo esc_html( $review['name'] ); ?></span>
							<div
								class="review-card__stars"
								aria-label="<?php
									/* translators: %d: star rating (1–5) */
									printf( esc_attr__( '%d out of 5 stars', 'genzee-jewels' ), absint( $review['rating'] ) );
								?>"
							>
								<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<svg
									class="star-icon<?php echo $i <= $review['rating'] ? ' star-icon--filled' : ''; ?>"
									width="10"
									height="10"
									viewBox="0 0 16 16"
									aria-hidden="true"
									focusable="false"
								>
									<path d="M8 1L10 6L15 6.5L11 10L12.5 15L8 12L3.5 15L5 10L1 6.5L6 6Z"/>
								</svg>
								<?php endfor; ?>
							</div>
						</div>
						<?php if ( $review['verified'] ) : ?>
						<span class="review-card__verified"><?php esc_html_e( '✓ Verified', 'genzee-jewels' ); ?></span>
						<?php endif; ?>
					</div>
					<p class="review-card__text"><?php echo esc_html( $review['text'] ); ?></p>
					<span class="review-card__product">
						<?php
						printf(
							/* translators: %s: product name */
							esc_html__( 'on %s', 'genzee-jewels' ),
							esc_html( $review['product'] )
						);
						?>
					</span>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>

	</div>
</section>
