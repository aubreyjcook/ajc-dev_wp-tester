<?php
/**
 * Template Name: Full width
 * Template Post Type: page
 * Description: Page content without the sidebar.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="content-area">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', 'page' );

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	?>
</div>
<?php
get_footer();
