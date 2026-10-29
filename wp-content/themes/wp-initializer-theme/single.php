<?php
/**
 * Single post.
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
		get_template_part( 'template-parts/content', get_post_type() );

		the_post_navigation(
			array(
				'prev_text' => __( 'Previous: %title', 'wp-initializer-theme' ),
				'next_text' => __( 'Next: %title', 'wp-initializer-theme' ),
			)
		);

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	?>
</div>
<?php
get_sidebar();
get_footer();
