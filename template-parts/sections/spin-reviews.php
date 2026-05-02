<?php
/**
 * Section: Reviews
 *
 * Shows approved WooCommerce product reviews only.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reviews = array();
$tones   = array( 'butter', 'lilac', 'tangerine', 'mint' );

if ( class_exists( 'WooCommerce' ) ) {
	$review_comments = get_comments(
		array(
			'status'    => 'approve',
			'type'      => 'review',
			'post_type' => 'product',
			'number'    => 4,
		)
	);

	foreach ( $review_comments as $index => $review_comment ) {
		if ( ! $review_comment instanceof WP_Comment ) {
			continue;
		}

		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $review_comment->comment_post_ID ) : null;
		$rating  = (int) get_comment_meta( $review_comment->comment_ID, 'rating', true );

		if ( ! $product instanceof WC_Product || $rating <= 0 ) {
			continue;
		}

		$author  = $review_comment->comment_author ? $review_comment->comment_author : __( 'Customer', 'genzee-jewels' );
		$initial = function_exists( 'mb_substr' ) ? mb_substr( $author, 0, 1 ) : substr( $author, 0, 1 );
		$initial = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $initial ) : strtoupper( $initial );

		$reviews[] = array(
			'name'     => $author,
			'initial'  => $initial,
			'tone'     => $tones[ $index % count( $tones ) ],
			'rating'   => min( 5, $rating ),
			'text'     => wp_trim_words( wp_strip_all_tags( $review_comment->comment_content ), 28 ),
			'product'  => $product->get_name(),
			'verified' => function_exists( 'wc_review_is_from_verified_owner' ) ? wc_review_is_from_verified_owner( $review_comment->comment_ID ) : false,
		);
	}
}
?>

<section class="section-spin-reviews">
	<div class="spin-reviews-wrap">
		<div class="reviews-panel">
			<div class="reviews-panel__header">
				<span class="reviews-panel__eyebrow"><?php esc_html_e( 'CUSTOMER REVIEWS', 'genzee-jewels' ); ?></span>
				<h2 class="reviews-panel__heading">
					&ldquo;<?php esc_html_e( 'from the product pages', 'genzee-jewels' ); ?>&rdquo;
				</h2>
			</div>

			<?php if ( empty( $reviews ) ) : ?>
				<div class="review-card">
					<p class="review-card__text">
						<?php esc_html_e( 'Approved WooCommerce product reviews will appear here once customers leave them.', 'genzee-jewels' ); ?>
					</p>
				</div>
			<?php else : ?>
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
							<span class="review-card__verified"><?php esc_html_e( 'Verified owner', 'genzee-jewels' ); ?></span>
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
			<?php endif; ?>
		</div>
	</div>
</section>
