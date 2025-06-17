<?php
/**
 * PTRE Plugin - Custom Post Types and Taxonomies
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTRE_Custom_Post_Types {
    public function __construct() {
        add_action('init', [$this, 'register_custom_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'add_rewrite_rules']);
    }

    /**
     * Register custom post types: Properties, Team, Cities
     */
    public function register_custom_post_types() {
        // Register Properties post type
        register_post_type('properties',
            array(
                'labels' => array(
                    'name' => __('Properties'),
                    'singular_name' => __('Property')
                ),
                'public' => true,
                'has_archive' => false,
                'rewrite' => array('slug' => 'properties'),
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                'menu_icon' => 'dashicons-building'
            )
        );

        // Register Team post type
        register_post_type('team',
            array(
                'labels' => array(
                    'name' => __('Team Members'),
                    'singular_name' => __('Team Member')
                ),
                'public' => true,
                'has_archive' => false,
                'supports' => array('title', 'editor', 'thumbnail'),
                'menu_icon' => 'dashicons-groups'
            )
        );

        // Register Cities post type
        register_post_type('cities',
            array(
                'labels' => array(
                    'name' => __('Cities'),
                    'singular_name' => __('City')
                ),
                'public' => true,
                'has_archive' => false,
                'supports' => array('title', 'editor', 'thumbnail'),
                'menu_icon' => 'dashicons-location'
            )
        );
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Cities Categories taxonomy
        register_taxonomy(
            'cities_categories',
            'cities',
            array(
                'label' => __('Categories'),
                'rewrite' => array('slug' => 'cities-category'),
                'hierarchical' => true,
                'show_admin_column' => true
            )
        );
    }

    /**
     * Add custom rewrite rules
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            'properties/([^/]+)/([0-9]+)/?$',
            'index.php?post_type=properties&mls=$matches[2]',
            'top'
        );
        add_rewrite_rule(
            'en/properties/([^/]+)/([0-9]+)/?$',
            'index.php?post_type=properties&mls=$matches[2]&lang=en',
            'top'
        );
    }
}