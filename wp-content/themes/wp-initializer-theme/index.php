<?php
/**
 * Main template. Used for the blog index when no more specific template matches.
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
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="archive-header">
			<h1 class="archive-title"><?php echo esc_html( single_post_title( '', false ) ); ?></h1>
		</header>
	<?php elseif ( is_home() ) : ?>
		<header class="archive-header">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Posts', 'wp-initializer-theme' ); ?></h1>
		</header>
	<?php endif; ?>
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		}
		the_posts_navigation();
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_sidebar();
get_footer();
