<?php
/**
 * The blocks-specific functionality of the plugin.
 *
 * @package    Ptre_Plugin
 * @subpackage Ptre_Plugin/includes
 */
class Ptre_Blocks {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        error_log( 'PTRE_Blocks: __construct() called.' ); // Debugging
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Note: Hooks are registered through the loader in Ptre_Functions::define_public_hooks()
        // No direct hook registration needed here
    }

    /**
     * Register standard WordPress blocks.
     *
     * @since    1.0.0
     */
    public function register_blocks() {
        error_log( 'PTRE_Blocks: register_blocks() called.' ); // Debugging
        // Example of standard block registration (if needed)
        // register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'build/properties-grid' );
        // register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'build/featured-properties' );
        // register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'build/city-properties' );
        // register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'build/agent-properties' );
    }

    /**
     * Register ACF blocks.
     *
     * @since    1.0.0
     */
    public function register_acf_blocks() {
        error_log( 'PTRE_Blocks: register_acf_blocks() called.' ); // Debugging
        if ( function_exists( 'acf_register_block_type' ) ) {
            acf_register_block_type( array(
                'name'            => 'ptre-hero-section',
                'title'           => __( 'PTRE Hero Section', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the hero section of the homepage.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_hero_section_block_render_callback',
                'category'        => 'common', // or 'layout', 'formatting', 'widgets', 'embed'
                'icon'            => 'align-wide', // Dashicon
                'keywords'        => array( 'hero', 'section', 'homepage' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );
        } else {
            error_log( 'PTRE_Blocks: acf_register_block_type function does not exist.' ); // Debugging
        }
    }
}