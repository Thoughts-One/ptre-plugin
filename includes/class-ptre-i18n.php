<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Ptre_Plugin
 * @subpackage Ptre_Plugin/includes
 */
class Ptre_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        // This assumes your .mo files are in the /languages/ folder of your plugin.
        // The 'ptre-plugin' text domain should match the one defined in your plugin's header.
        load_plugin_textdomain(
            'ptre-plugin',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }
}