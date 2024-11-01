<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/dev_vahid/
 * @since             1.0.0
 * @package           Simply_Change_Author_Url
 *
 * @wordpress-plugin
 * Plugin Name:       Simply Change Author URL
 * Plugin URI:        https://wordpress.org/plugins/simply-change-author-url/
 * Description:       Changes wordpress user slug for security, no one can see username of people registered on your site.
 * Version:           1.1.2
 * Author:            vahid
 * Author URI:        https://profiles.wordpress.org/dev_vahid/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simply-change-author-url
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
! defined( 'SIMPLY_CHANGE_AUTHOR_URL_VERSION' ) ? define( 'SIMPLY_CHANGE_AUTHOR_URL_VERSION', '1.1.2' ) : null;

if ( ! function_exists( 'activate_simply_change_author_url' ) ) {
	/**
	 * The code that runs during plugin activation.
	 */
	function activate_simply_change_author_url() {
		set_transient( 'simply_change_author_url_changed', true, 30 );
	}
}


if ( ! function_exists( 'deactivate_simply_change_author_url' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * flush rewrite rules doesn't work on plugin deactivation
	 */
	function deactivate_simply_change_author_url() {
		update_option( 'rewrite_rules', '' );
	}
}


register_activation_hook( __FILE__, 'activate_simply_change_author_url' );
register_deactivation_hook( __FILE__, 'deactivate_simply_change_author_url' );

/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simply-change-author-url.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_simply_change_author_url() {
	$plugin = new Simply_Change_Author_Url();
	$plugin->run();
}

run_simply_change_author_url();
