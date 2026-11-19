<?php get_header(); ?>
<section class="page-content">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?>><h1><?php the_title(); ?></h1><?php the_content(); ?></article>
	<?php endwhile; else : ?><p><?php esc_html_e( 'Nothing has been published yet.', 'genzee-prototype' ); ?></p><?php endif; ?>
</section>
<?php get_footer(); ?>
