<?php
/**
 * Plugin Name: PTRE Core Functionality
 * Description: Handles custom functionality for Peter Thompson Real Estate
 * Version: 1.0.0
 * Author: Peter Thompson Real Estate
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

// Initialize plugin components
add_action('plugins_loaded', function() {
    new PTRE_API_Handler();
    new PTRE_Shortcodes();
    new PTRE_Blocks();
    new PTRE_Functions();
});