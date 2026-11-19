<?php
/** Default WooCommerce wrapper for shop, products, cart and checkout. */
get_header();
?>
<section class="woocommerce-content">
	<?php woocommerce_content(); ?>
</section>
<?php get_footer(); ?>
