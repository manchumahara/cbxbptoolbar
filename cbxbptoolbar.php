<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codeboxr.com
 * @since             1.0.0
 * @package           Cbxbptoolbar
 *
 * @wordpress-plugin
 * Plugin Name:       CBX BuddyPress Toolbar
 * Plugin URI:        https://codeboxr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxbptoolbar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

	//plugin definition specific constants
	defined( 'CBX_CBXBPTOOLBR_PLUGIN_NAME' ) or define( 'CBX_CBXBPTOOLBR_PLUGIN_NAME', 'cbxbptoolbar' );
	defined( 'CBX_CBXBPTOOLBR_PLUGIN_VERSION' ) or define( 'CBX_CBXBPTOOLBR_PLUGIN_VERSION', '1.0.0' );
	defined( 'CBX_CBXBPTOOLBR_PLUGIN_BASE_NAME' ) or define( 'CBX_CBXBPTOOLBR_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
	defined( 'CBX_CBXBPTOOLBR_PLUGIN_ROOT_PATH' ) or define( 'CBX_CBXBPTOOLBR_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
	defined( 'CBX_CBXBPTOOLBR_PLUGIN_ROOT_URL' ) or define( 'CBX_CBXBPTOOLBR_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxbptoolbar-activator.php
 */
function activate_cbxbptoolbar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxbptoolbar-activator.php';
	Cbxbptoolbar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxbptoolbar-deactivator.php
 */
function deactivate_cbxbptoolbar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxbptoolbar-deactivator.php';
	Cbxbptoolbar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cbxbptoolbar' );
register_deactivation_hook( __FILE__, 'deactivate_cbxbptoolbar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxbptoolbar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxbptoolbar() {

	$plugin = new Cbxbptoolbar();
	$plugin->run();

}
run_cbxbptoolbar();
