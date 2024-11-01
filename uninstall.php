<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://profiles.wordpress.org/dev_vahid/
 * @since      1.0.0
 *
 * @package    Simply_Change_Author_Url
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
update_option( 'rewrite_rules', '' );
