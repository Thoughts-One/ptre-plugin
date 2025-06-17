<?php
/**
 * Plugin Name: PTRE Plugin
 * Plugin URI:  https://peterthompson.ca
 * Description: Companion plugin for the PTRE Theme, handling custom post types, taxonomies, shortcodes, and other non-theme-specific functionalities.
 * Version:     1.0.0
 * Author:      Marketing Websites
 * Author URI:  https://marketingwebsites.ca
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ptre-plugin
 * Domain Path: /languages
 *
 * @package PTRE_Plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Current plugin version.
 */
define( 'PTRE_PLUGIN_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ptre-functions.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ptre-custom-post-types.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ptre-api-handler.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ptre-blocks.php'; // This file should register the block category
require plugin_dir_path( __FILE__ ) . 'includes/blocks/hero-section-block.php'; // Include the new block file

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file means
 * that all of the hooks will be defined.
 */
function run_ptre_plugin() {
    $plugin = new Ptre_Functions();
    $plugin->run();
}
run_ptre_plugin();

// Load MW Properties shortcode overrides from the plugin
// This ensures the plugin's shortcode templates are loaded before the theme's, if any.
add_action('plugins_loaded', function() {
    $plugin_shortcode_path = plugin_dir_path(__FILE__) . 'mw-properties-templates/shortcodes/';
    if (file_exists($plugin_shortcode_path . 'nearby-cities.php')) {
        require_once $plugin_shortcode_path . 'nearby-cities.php';
    }
    // Add other shortcode overrides here if they exist in the plugin
});