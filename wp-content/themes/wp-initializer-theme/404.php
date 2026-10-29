<?php
/**
 * Not found.
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
	<header class="archive-header">
		<h1 class="archive-title"><?php esc_html_e( 'Page not found', 'wp-initializer-theme' ); ?></h1>
		<p><?php esc_html_e( 'The address may have changed, or the page may have been removed.', 'wp-initializer-theme' ); ?></p>
		<?php get_search_form(); ?>
	</header>
</div>
<?php
get_sidebar();
get_footer();
