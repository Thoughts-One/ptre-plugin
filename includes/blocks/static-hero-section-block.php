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
 * Registers the static hero section block from its block.json.
 */
function ptre_plugin_register_static_hero_section_block_from_metadata() {
    if ( ! function_exists( 'register_block_type_from_metadata' ) ) {
        return;
    }

    register_block_type_from_metadata( __DIR__ );
}

add_action( 'init', 'ptre_plugin_register_static_hero_section_block_from_metadata' );