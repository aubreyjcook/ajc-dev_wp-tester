<?php
/**
 * Sidebar. Renders nothing until the Sidebar widget area has widgets.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
