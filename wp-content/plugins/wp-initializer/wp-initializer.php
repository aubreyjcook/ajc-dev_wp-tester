<?php
/**
 * Plugin Name:       WP Initializer
 * Description:       Starter plugin with a settings screen, shortcode, and dynamic block.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Your Name
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-initializer
 *
 * Keep the Version header in sync with WP_INITIALIZER_VERSION.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_INITIALIZER_VERSION', '0.1.0' );
define( 'WP_INITIALIZER_FILE', __FILE__ );
define( 'WP_INITIALIZER_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_INITIALIZER_URL', plugin_dir_url( __FILE__ ) );

require_once WP_INITIALIZER_DIR . 'includes/class-activator.php';
require_once WP_INITIALIZER_DIR . 'includes/class-settings.php';
require_once WP_INITIALIZER_DIR . 'includes/class-shortcode.php';
require_once WP_INITIALIZER_DIR . 'includes/class-assets.php';
require_once WP_INITIALIZER_DIR . 'includes/class-plugin.php';

register_activation_hook( __FILE__, array( '\WpInitializer\Activator', 'activate' ) );

/**
 * Wire plugin hooks after WordPress has loaded plugins.
 */
function wp_initializer_bootstrap() {
	( new \WpInitializer\Plugin() )->hooks();
}
add_action( 'plugins_loaded', 'wp_initializer_bootstrap' );
