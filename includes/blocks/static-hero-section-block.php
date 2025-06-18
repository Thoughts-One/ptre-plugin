<?php
/**
 * Static Hero Section Block
 *
 * @package PTRE_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Renders the static hero section block.
 *
 * @param array $block The block attributes.
 * @param string $content The block content.
 * @param bool $is_preview True during backend preview.
 * @param int $post_id The post ID.
 * @param array $wp_block The WP_Block object.
 * @param array $context The context of the block.
 */
function ptre_plugin_static_hero_section_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = null, $context = [] ) {
    // Placeholder content for the static hero section.
    // This can be modified directly in the file or via block attributes if needed later.
    $output = '<div class="static-hero-section" style="background-color: #f0f0f0; padding: 50px 20px; text-align: center;">';
    $output .= '<h1 style="color: #333; font-size: 3em; margin-bottom: 20px;">Welcome to Peter Thompson Realty</h1>';
    $output .= '<p style="color: #666; font-size: 1.2em; line-height: 1.5;">Your trusted partner in real estate. Find your dream home today!</p>';
    $output .= '<img src="https://via.placeholder.com/800x400?text=Placeholder+Image" alt="Hero Image" style="max-width: 100%; height: auto; margin-top: 30px;">';
    $output .= '</div>';

    echo $output;
}

/**
 * Registers the static hero section block.
 */
function ptre_plugin_register_static_hero_section_block() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    register_block_type( 'ptre-plugin/static-hero-section', array(
        'editor_script'   => 'ptre-plugin-static-hero-section-block-editor', // Optional: for JS in editor
        'editor_style'    => 'ptre-plugin-static-hero-section-block-editor-style', // Optional: for CSS in editor
        'style'           => 'ptre-plugin-static-hero-section-block-style', // Optional: for frontend CSS
        'render_callback' => 'ptre_plugin_static_hero_section_block_render_callback',
        'attributes'      => array(
            // Define attributes here if you want to make parts of the block editable without ACF.
            // For now, it's static content.
        ),
        'category'        => 'ptre-blocks', // Custom category for PTRE blocks
        'icon'            => 'star-filled', // Dashicon icon for the block
        'keywords'        => array( 'hero', 'static', 'banner' ),
        'supports'        => array(
            'align' => true,
            'html'  => false,
        ),
    ) );
}

add_action( 'init', 'ptre_plugin_register_static_hero_section_block' );

// Enqueue block assets (optional, if you have editor/frontend scripts/styles)
function ptre_plugin_static_hero_section_block_assets() {
    if ( ! is_admin() ) {
        wp_enqueue_style(
            'ptre-plugin-static-hero-section-block-style',
            plugin_dir_url( __FILE__ ) . 'static-hero-section-block.css',
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'static-hero-section-block.css' )
        );
    }
}
add_action( 'enqueue_block_assets', 'ptre_plugin_static_hero_section_block_assets' );