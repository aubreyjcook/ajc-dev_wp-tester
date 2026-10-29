<?php
/**
 * Plugin Name:       WP Initializer (Must-Use)
 * Description:       Always-on project bootstrap. Add site-wide hooks in this file.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Your Name
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Must-use plugins load on every request and cannot be deactivated from the
 * Plugins screen. WordPress only auto-loads PHP files in this directory, not
 * subdirectories — require those from here.
 *
 * This file is inert on purpose. Dropping the template into a site does not
 * change front-end or admin behavior until you add code below.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
