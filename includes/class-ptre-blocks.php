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

        // Register the static hero section block using block.json
        register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/static-hero-section-block' );

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
        
        // Since this method is called on acf/init hook, ACF should be ready
        // Simple safety check only
        if ( ! function_exists( 'acf_register_block_type' ) ) {
            error_log( 'PTRE_Blocks: acf_register_block_type function does not exist - this should not happen on acf/init hook.' ); // Debugging
            return;
        }
        
        error_log( 'PTRE_Blocks: ACF functions available, proceeding with block registration.' ); // Debugging
        
        if ( true ) {
            // Hero Section Block
            acf_register_block_type( array(
                'name'            => 'ptre-hero-section',
                'title'           => __( 'PTRE Hero Section', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the hero section of the homepage.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_hero_section_block_render_callback',
                'category'        => 'common',
                'icon'            => 'align-wide',
                'keywords'        => array( 'hero', 'section', 'homepage' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // Seller Experience Block
            acf_register_block_type( array(
                'name'            => 'ptre-seller-experience',
                'title'           => __( 'PTRE Seller Experience', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the seller experience section.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_seller_experience_block_render_callback',
                'category'        => 'common',
                'icon'            => 'businessman',
                'keywords'        => array( 'seller', 'experience', 'marketing' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // Buyer Experience Block
            acf_register_block_type( array(
                'name'            => 'ptre-buyer-experience',
                'title'           => __( 'PTRE Buyer Experience', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the buyer experience section.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_buyer_experience_block_render_callback',
                'category'        => 'common',
                'icon'            => 'groups',
                'keywords'        => array( 'buyer', 'experience', 'marketing' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // Ask Peter Block
            acf_register_block_type( array(
                'name'            => 'ptre-ask-peter',
                'title'           => __( 'PTRE Ask Peter', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the ask peter section.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_ask_peter_block_render_callback',
                'category'        => 'common',
                'icon'            => 'format-chat',
                'keywords'        => array( 'ask', 'peter', 'contact' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // About Block
            acf_register_block_type( array(
                'name'            => 'ptre-about',
                'title'           => __( 'PTRE About', 'ptre-plugin' ),
                'description'     => __( 'A custom block for the about section.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_about_block_render_callback',
                'category'        => 'common',
                'icon'            => 'admin-users',
                'keywords'        => array( 'about', 'profile', 'bio' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // Properties Block
            acf_register_block_type( array(
                'name'            => 'ptre-properties',
                'title'           => __( 'PTRE Properties', 'ptre-plugin' ),
                'description'     => __( 'A custom block for displaying properties.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_properties_block_render_callback',
                'category'        => 'common',
                'icon'            => 'admin-home',
                'keywords'        => array( 'properties', 'listings', 'real estate' ),
                'supports'        => array(
                    'align' => false,
                    'mode'  => false,
                ),
            ) );

            // Ellis Presents Block
            acf_register_block_type( array(
                'name'            => 'ptre-ellis-presents',
                'title'           => __( 'PTRE Ellis Presents', 'ptre-plugin' ),
                'description'     => __( 'A custom block for displaying Ellis Presents content.', 'ptre-plugin' ),
                'render_callback' => 'ptre_plugin_ellis_presents_block_render_callback',
                'category'        => 'common',
                'icon'            => 'format-video',
                'keywords'        => array( 'ellis', 'presents', 'videos' ),
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