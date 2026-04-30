<?php
/**
 * Homepage template (front-page.php).
 * WordPress uses this file (not home.php) when a static front page is set.
 *
 * Section order mirrors design_files/home.jsx.
 *
 * @package genzee-jewels
 */

get_header();

$genzee_home_sections = array(
	'hero',
	'trust-strip',
	'categories',
	'impulse-zone',
	'bestsellers',
	'combo-banner',
	'new-drops',
	'spin-reviews',
	'instagram-grid',
	'newsletter',
);
?>

	<?php foreach ( $genzee_home_sections as $genzee_home_section ) : ?>
		<?php
		$genzee_template = 'template-parts/sections/' . $genzee_home_section . '.php';

		if ( locate_template( $genzee_template ) ) {
			get_template_part( 'template-parts/sections/' . $genzee_home_section );
		} elseif ( current_user_can( 'manage_options' ) ) {
			printf(
				"\n<!-- Genzee theme missing homepage section: %s -->\n",
				esc_html( $genzee_template )
			);
		}
		?>
	<?php endforeach; ?>

<?php
get_footer();
