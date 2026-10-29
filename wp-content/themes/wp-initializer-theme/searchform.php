<?php
/**
 * Search form.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'wp-initializer-theme' ); ?></span>
		<input type="search" class="search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
	</label>
	<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'wp-initializer-theme' ); ?></button>
</form>
