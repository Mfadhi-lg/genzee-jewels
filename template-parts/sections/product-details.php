<?php
/**
 * PDP details section: description, material, care, specs, reviews, and sticky ATC.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = isset( $args ) && is_array( $args ) ? $args : array();
$product = isset( $args['product'] ) && $args['product'] instanceof WC_Product ? $args['product'] : null;

if ( ! $product ) {
	return;
}

$short_description = $product->get_short_description();
$description       = $product->get_description();
$material          = (string) $product->get_meta( 'genzee_material', true );
$care              = (string) $product->get_meta( 'genzee_care', true );
$specs             = (string) $product->get_meta( 'genzee_specs', true );
$attributes        = $product->get_attributes();
$detail_rows       = array();

if ( $material ) {
	$detail_rows[] = array(
		'label' => __( 'Material', 'genzee-jewels' ),
		'value' => $material,
	);
}

if ( $care ) {
	$detail_rows[] = array(
		'label' => __( 'Care', 'genzee-jewels' ),
		'value' => $care,
	);
}

if ( $specs ) {
	$detail_rows[] = array(
		'label' => __( 'Specs', 'genzee-jewels' ),
		'value' => $specs,
	);
}

if ( $product->get_sku() ) {
	$detail_rows[] = array(
		'label' => __( 'SKU', 'genzee-jewels' ),
		'value' => $product->get_sku(),
	);
}

if ( $product->has_weight() ) {
	$detail_rows[] = array(
		'label' => __( 'Weight', 'genzee-jewels' ),
		'value' => wc_format_weight( $product->get_weight() ),
	);
}

if ( $product->has_dimensions() ) {
	$detail_rows[] = array(
		'label' => __( 'Dimensions', 'genzee-jewels' ),
		'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
	);
}

foreach ( $attributes as $attribute ) {
	if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_visible() ) {
		continue;
	}

	$label  = wc_attribute_label( $attribute->get_name(), $product );
	$values = $attribute->is_taxonomy()
		? wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) )
		: $attribute->get_options();

	if ( empty( $values ) || is_wp_error( $values ) ) {
		continue;
	}

	$values = array_values( array_filter( array_map( 'wc_clean', $values ) ) );

	if ( empty( $values ) ) {
		continue;
	}

	$detail_rows[] = array(
		'label' => $label,
		'value' => implode( ', ', $values ),
	);
}

// ── Reviews data ──────────────────────────────────────────────────────────────
$avg_rating    = (float) $product->get_average_rating();
$total_reviews = (int) $product->get_rating_count();
$rating_counts = $product->get_rating_counts();
$has_ratings   = $total_reviews > 0;

$live_reviews = $has_ratings ? get_comments(
	array(
		'post_id'  => $product->get_id(),
		'status'   => 'approve',
		'type'     => 'review',
		'number'   => 3,
	)
) : array();

$color_classes = array( 'a', 'b', 'c', 'd', 'e' );

// ── Sticky ATC data ───────────────────────────────────────────────────────────
$sticky_atc_url    = $product->add_to_cart_url();
$sticky_atc_label  = $product->is_type( 'variable' )
	? __( 'Select Options', 'genzee-jewels' )
	: __( 'Add to Cart', 'genzee-jewels' );
$sticky_can_ajax_add = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
$sticky_price_html = wp_kses_post( $product->get_price_html() );
$sticky_image_html = wp_kses_post( $product->get_image(
	'thumbnail',
	array(
		'class' => 'pdp-sticky-atc__img',
		'alt'   => esc_attr( $product->get_name() ),
	)
) );
$sticky_name       = $product->get_name();
$is_on_sale        = $product->is_on_sale();
$regular_price     = $product->is_type( 'variable' ) ? (float) $product->get_variation_regular_price( 'min', true ) : (float) $product->get_regular_price();
$sale_price        = $product->is_type( 'variable' ) ? (float) $product->get_variation_sale_price( 'min', true ) : (float) $product->get_sale_price();
$has_sale_discount = $is_on_sale && $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price;
$discount_percent  = $has_sale_discount ? (int) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ) : 0;

// ── Buy now URL ───────────────────────────────────────────────────────────────
$buy_now_url = '#pdp-product-title';
if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && function_exists( 'wc_get_checkout_url' ) ) {
	$buy_now_url = add_query_arg(
		array(
			'add-to-cart' => $product->get_id(),
			'quantity'    => 1,
		),
		wc_get_checkout_url()
	);
}
?>

<section class="pdp-details" aria-labelledby="pdp-details-title">
	<div class="container pdp-details__grid">
		<div class="pdp-details__description">
			<p class="pdp-details__eyebrow"><?php esc_html_e( 'The Details', 'genzee-jewels' ); ?></p>
			<h2 class="pdp-details__title" id="pdp-details-title">
				<?php esc_html_e( 'Product Details', 'genzee-jewels' ); ?>
			</h2>

			<?php if ( $short_description ) : ?>
				<div class="pdp-details__content pdp-details__content--short">
					<?php echo wp_kses_post( wpautop( $short_description ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<div class="pdp-details__content">
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				</div>
			<?php else : ?>
				<?php if ( ! $short_description ) : ?>
					<p class="pdp-details__muted"><?php esc_html_e( 'Product description will be available soon.', 'genzee-jewels' ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! empty( $detail_rows ) ) : ?>
				<dl class="pdp-details__list">
					<?php foreach ( $detail_rows as $detail_row ) : ?>
						<dt><?php echo esc_html( $detail_row['label'] ); ?></dt>
						<dd><?php echo esc_html( $detail_row['value'] ); ?></dd>
					<?php endforeach; ?>
				</dl>
			<?php else : ?>
				<ul class="pdp-details__fallback">
					<li><?php esc_html_e( 'Check the product photos and selected variant before ordering.', 'genzee-jewels' ); ?></li>
					<?php if ( $product->is_in_stock() ) : ?>
						<li><?php esc_html_e( 'This item is currently listed as in stock.', 'genzee-jewels' ); ?></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_product_additional_information.
			 *
			 * @hooked wc_display_product_attributes - 10
			 */
			do_action( 'woocommerce_product_additional_information', $product );
			?>
		</div>

		<div class="pdp-details__review-summary" aria-label="<?php esc_attr_e( 'Review summary', 'genzee-jewels' ); ?>">
			<div class="pdp-details__review-head">
				<div>
					<?php if ( $has_ratings ) : ?>
						<p class="pdp-details__review-score" aria-hidden="true">
							<?php echo esc_html( number_format( $avg_rating, 1 ) ); ?>
						</p>
						<?php
						get_template_part(
							'template-parts/components/stars',
							null,
							array( 'rating' => $avg_rating )
						);
						?>
						<p class="pdp-details__review-count">
							<?php
							printf(
								/* translators: %s: formatted review count number */
								wp_kses_post( __( 'Based on %s reviews', 'genzee-jewels' ) ),
								'<strong>' . esc_html( number_format_i18n( $total_reviews ) ) . '</strong>'
							);
							?>
						</p>
					<?php else : ?>
						<p class="pdp-details__review-empty"><?php esc_html_e( 'No reviews yet', 'genzee-jewels' ); ?></p>
						<p class="pdp-details__review-count"><?php esc_html_e( 'Be the first to share your thoughts.', 'genzee-jewels' ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $has_ratings ) : ?>
					<a href="#respond" class="pdp-details__write-review"><?php esc_html_e( 'Write a review', 'genzee-jewels' ); ?></a>
				<?php endif; ?>
			</div>

			<?php if ( $has_ratings ) : ?>
				<div class="pdp-details__review-bars" aria-label="<?php esc_attr_e( 'Rating breakdown', 'genzee-jewels' ); ?>">
					<?php foreach ( array( 5, 4, 3, 2, 1 ) as $star ) :
						$star_count = isset( $rating_counts[ $star ] ) ? (int) $rating_counts[ $star ] : 0;
						$star_pct   = ( $has_ratings && $total_reviews > 0 )
							? (int) round( $star_count / $total_reviews * 100 )
							: 0;
						$fill_class = $star >= 4 ? 'pdp-details__bar-fill--green' : 'pdp-details__bar-fill--hot';
					?>
					<div class="pdp-details__bar-row">
						<span class="pdp-details__bar-label" aria-hidden="true"><?php echo esc_html( $star ); ?>&#9733;</span>
						<progress
							class="pdp-details__bar-track <?php echo esc_attr( $fill_class ); ?>"
							value="<?php echo esc_attr( $star_pct ); ?>"
							max="100"
							aria-label="<?php
							/* translators: 1: star count 2: percentage */
							printf( esc_attr__( '%1$d star: %2$d%%', 'genzee-jewels' ), $star, $star_pct );
							?>"><?php echo esc_html( $star_pct ); ?>%</progress>
						<span class="pdp-details__bar-pct" aria-hidden="true"><?php echo esc_html( $star_pct ); ?>%</span>
					</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( ! empty( $live_reviews ) ) : ?>
<section class="pdp-reviews" aria-labelledby="pdp-reviews-title">
	<div class="container">
		<h2 class="pdp-reviews__heading" id="pdp-reviews-title"><?php esc_html_e( 'Recent Reviews', 'genzee-jewels' ); ?></h2>
		<div class="pdp-reviews__cards">
			<?php foreach ( $live_reviews as $ridx => $review ) :
				$rev_author  = get_comment_author( $review );
				$rev_rating  = (int) get_comment_meta( $review->comment_ID, 'rating', true );
				$rev_text    = get_comment_text( $review );
				$rev_initial = mb_strtoupper( mb_substr( $rev_author, 0, 1 ) );
				$rev_class   = $color_classes[ $ridx % count( $color_classes ) ];
				$is_verified = function_exists( 'wc_review_is_from_verified_owner' ) && wc_review_is_from_verified_owner( $review->comment_ID );
				if ( $rev_rating < 1 ) {
					$rev_rating = 5;
				}
			?>
			<article class="pdp-review-card">
				<div class="pdp-review-card__header">
					<div class="pdp-review-card__avatar pdp-review-card__avatar--<?php echo esc_attr( $rev_class ); ?>" aria-hidden="true">
						<?php echo esc_html( $rev_initial ); ?>
					</div>
					<div class="pdp-review-card__meta">
						<p class="pdp-review-card__name"><?php echo esc_html( $rev_author ); ?></p>
						<?php
						get_template_part( 'template-parts/components/stars', null, array(
							'rating' => (float) $rev_rating,
							'size'   => 'sm',
						) );
						?>
					</div>
				</div>
				<p class="pdp-review-card__text"><?php echo esc_html( $rev_text ); ?></p>
				<?php if ( $is_verified ) : ?>
					<span class="pdp-review-card__verified"><?php esc_html_e( 'Verified purchase', 'genzee-jewels' ); ?></span>
				<?php endif; ?>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<div id="pdp-sticky-atc"
	class="pdp-sticky-atc is-hidden"
	role="region"
	aria-label="<?php esc_attr_e( 'Quick purchase', 'genzee-jewels' ); ?>">

	<?php if ( $sticky_image_html ) : ?>
	<div class="pdp-sticky-atc__thumb" aria-hidden="true">
		<?php echo $sticky_image_html; // sanitised via wp_kses_post ?>
	</div>
	<?php endif; ?>

	<div class="pdp-sticky-atc__info">
		<p class="pdp-sticky-atc__name"><?php echo esc_html( $sticky_name ); ?></p>
		<div class="pdp-sticky-atc__price-row">
			<div class="pdp-sticky-atc__price">
				<?php echo $sticky_price_html; // sanitised via wp_kses_post ?>
			</div>
			<?php if ( $has_sale_discount ) : ?>
				<span class="pdp-sticky-atc__discount">
					<?php
					printf(
						/* translators: %d: sale discount percentage. */
						esc_html__( '%d%% OFF', 'genzee-jewels' ),
						absint( $discount_percent )
					);
					?>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<div class="pdp-sticky-atc__actions">
		<a href="<?php echo esc_url( $sticky_atc_url ); ?>"
			class="pdp-sticky-atc__btn<?php echo $sticky_can_ajax_add ? ' add_to_cart_button ajax_add_to_cart' : ''; ?>"
			<?php if ( $sticky_can_ajax_add ) : ?>
				rel="nofollow"
				data-product_id="<?php echo esc_attr( (int) $product->get_id() ); ?>"
				data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				data-quantity="1"
			<?php endif; ?>>
			<?php echo esc_html( $sticky_atc_label ); ?>
		</a>
		<a href="<?php echo esc_url( $buy_now_url ); ?>" class="pdp-sticky-atc__buy-now">
		⚡ <?php esc_html_e( 'Buy now', 'genzee-jewels' ); ?>
		</a>
	</div>
</div>
