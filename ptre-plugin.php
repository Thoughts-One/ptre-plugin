<?php
/*
Plugin Name:  PTRE Plugin
Plugin URI:   https://peterthompson.ca
Description:  Custom plugin for peterthompson.ca
Version:      1.0
Author:       Peter Thompson Real Estate
Author URI:   https://peterthompson.ca
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  ptre-plugin
Domain Path:  /languages
*/

defined('ABSPATH') || exit;

// Define plugin constants
define('PTRE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PTRE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load required files
require_once PTRE_PLUGIN_DIR . 'includes/class-ptre-api-handler.php';
require_once PTRE_PLUGIN_DIR . 'includes/class-ptre-shortcodes.php';
require_once PTRE_PLUGIN_DIR . 'includes/class-ptre-blocks.php';
require_once PTRE_PLUGIN_DIR . 'inc/class-ptre-functions.php';
require_once PTRE_PLUGIN_DIR . 'includes/class-ptre-custom-post-types.php';
require_once PTRE_PLUGIN_DIR . 'mw-properties-templates/shortcodes/nearby-cities.php';

// Initialize plugin components
add_action('plugins_loaded', function() {
    new PTRE_API_Handler();
    new PTRE_Shortcodes();
    new PTRE_Blocks();
    new PTRE_Functions();
    new PTRE_Custom_Post_Types();
});