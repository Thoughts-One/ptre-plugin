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
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Hook into ACF to register blocks
        add_action( 'acf/init', array( $this, 'register_acf_blocks' ) );
        // Hook into WordPress init for standard blocks (if any)
        add_action( 'init', array( $this, 'register_blocks' ) );
    }

    /**
     * Register standard WordPress blocks.
     *
     * @since    1.0.0
     */
    public function register_blocks() {
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
        }
    }
}