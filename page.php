<?php
/**
 * Generic page template.
 *
 * @package genzee-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$genzee_is_account  = function_exists( 'is_account_page' ) && is_account_page();
$genzee_is_cart     = function_exists( 'is_cart' ) && is_cart();
$genzee_is_checkout = function_exists( 'is_checkout' ) && is_checkout();

if ( ! $genzee_is_cart && function_exists( 'wc_get_page_id' ) ) {
	$genzee_cart_page_id = (int) wc_get_page_id( 'cart' );
	$genzee_is_cart     = $genzee_cart_page_id > 0 && is_page( $genzee_cart_page_id );
}
$genzee_flush       = $genzee_is_account || $genzee_is_cart || $genzee_is_checkout;
?>

<div class="page-template page-template--generic<?php echo $genzee_flush ? ' page-template--flush' : ''; ?>">

	<?php if ( ! $genzee_flush ) : ?><div class="container"><?php endif; ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
		$hide_page_title = $genzee_is_account || $genzee_is_cart || $genzee_is_checkout;

		$page_content          = get_post_field( 'post_content', get_the_ID() );
		$is_empty_page_content = '' === trim( $page_content );
		$has_account_output    = has_shortcode( $page_content, 'woocommerce_my_account' )
			|| has_block( 'woocommerce/my-account', get_post() );
		$has_checkout_output   = has_shortcode( $page_content, 'woocommerce_checkout' )
			|| has_block( 'woocommerce/checkout', get_post() );
		$has_cart_output       = has_shortcode( $page_content, 'woocommerce_cart' )
			|| has_block( 'woocommerce/cart', get_post() );
		?>

		<?php if ( ! $genzee_flush ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-entry' ); ?>>
			<?php if ( ! $hide_page_title ) : ?>
				<header class="page-entry__header">
					<h1 class="page-entry__title"><?php the_title(); ?></h1>
				</header>
			<?php endif; ?>
		<?php endif; ?>

			<div class="page-entry__content<?php echo $genzee_flush ? ' page-entry__content--flush' : ''; ?>">
				<?php
				if ( $genzee_is_account && $is_empty_page_content && ! $has_account_output ) {
					echo do_shortcode( '[woocommerce_my_account]' );
				} elseif ( $genzee_is_cart && $is_empty_page_content && ! $has_cart_output ) {
					echo do_shortcode( '[woocommerce_cart]' );
				} elseif ( $genzee_is_checkout && $is_empty_page_content && ! $has_checkout_output ) {
					echo do_shortcode( '[woocommerce_checkout]' );
				} else {
					the_content();
				}

				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'genzee-jewels' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

		<?php if ( ! $genzee_flush ) : ?>
		</article>
		<?php endif; ?>

	<?php endwhile; ?>

	<?php if ( ! $genzee_flush ) : ?></div><?php endif; ?>

</div>

<?php
get_footer();
