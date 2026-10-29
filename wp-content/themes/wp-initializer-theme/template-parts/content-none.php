<?php
/**
 * Empty loop.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="no-results">
	<h2><?php esc_html_e( 'Nothing found', 'wp-initializer-theme' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No posts matched your search.', 'wp-initializer-theme' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts yet.', 'wp-initializer-theme' ); ?></p>
	<?php endif; ?>
</section>
