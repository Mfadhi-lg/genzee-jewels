<?php
/**
 * Section: Reviews
 *
 * Hardcoded review cards for the homepage social-proof block.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reviews = array(
	array(
		'name'     => 'Priya S.',
		'initial'  => 'P',
		'tone'     => 'butter',
		'rating'   => 5,
		'text'     => 'Legit obsessed, the hoops came in the cutest pink pouch. It feels like a gift to myself every time I open it.',
		'product'  => 'Y2K Chrome Hoops',
		'verified' => true,
	),
	array(
		'name'     => 'Ananya K.',
		'initial'  => 'A',
		'tone'     => 'lilac',
		'rating'   => 5,
		'text'     => "Quality for Rs 299? Shook. Wore them to a wedding and got asked where they're from like 5 times.",
		'product'  => 'Butter Heart Huggies',
		'verified' => true,
	),
	array(
		'name'     => 'Riya M.',
		'initial'  => 'R',
		'tone'     => 'tangerine',
		'rating'   => 5,
		'text'     => "The packaging alone is chef's kiss. Feels premium for what I paid. Repeat customer for life.",
		'product'  => 'Pearl Baby Lariat',
		'verified' => true,
	),
	array(
		'name'     => 'Isha B.',
		'initial'  => 'I',
		'tone'     => 'mint',
		'rating'   => 4,
		'text'     => 'Super dainty and layers beautifully with my other chains. Not the sturdiest, but for the price it is a 10/10.',
		'product'  => 'Layered Dainty Chain',
		'verified' => true,
	),
);
?>

<section class="section-spin-reviews">
	<div class="spin-reviews-wrap">
		<div class="reviews-panel">
			<div class="reviews-panel__header">
				<span class="reviews-panel__eyebrow"><?php esc_html_e( 'REAL REVIEWS', 'genzee-jewels' ); ?></span>
				<h2 class="reviews-panel__heading">
					&ldquo;<?php esc_html_e( 'im obsessed', 'genzee-jewels' ); ?>&rdquo;<em><?php esc_html_e( ' - everyone', 'genzee-jewels' ); ?></em>
				</h2>
			</div>

			<ul class="reviews-grid" aria-label="<?php esc_attr_e( 'Customer reviews', 'genzee-jewels' ); ?>">
				<?php foreach ( $reviews as $review ) : ?>
				<li class="review-card">
					<div class="review-card__top">
						<div
							class="review-card__avatar review-card__avatar--<?php echo esc_attr( $review['tone'] ); ?>"
							aria-hidden="true"
						>
							<?php echo esc_html( $review['initial'] ); ?>
						</div>
						<div class="review-card__meta">
							<span class="review-card__name"><?php echo esc_html( $review['name'] ); ?></span>
							<div
								class="review-card__stars"
								aria-label="<?php
									/* translators: %d: star rating (1-5) */
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
						<span class="review-card__verified"><?php esc_html_e( 'Verified', 'genzee-jewels' ); ?></span>
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
