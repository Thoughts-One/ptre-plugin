<?php
/**
 * PTRE Plugin Functions
 * 
 * Contains functions migrated from peter-thompson/functions.php
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTRE_Functions {
    public function __construct() {
        // Initialize all hooks
        $this->init_hooks();
    }

    private function init_hooks() {
        // Schema and SEO
        add_filter('wpseo_json_ld_output', [$this, 'add_real_estate_schema_to_yoast'], 20);
        add_filter('wpseo_json_ld_output', [$this, 'add_aggregate_ratings'], 20);
        add_filter('wpseo_canonical', [$this, 'fix_property_canonical'], 99);
        add_action('wp_head', [$this, 'output_property_canonical'], 1);
        
        // Multilingual
        add_action('wp_head', [$this, 'fix_property_hreflang_tags'], 0);
        add_filter('wpml_hreflangs', [$this, 'remove_xdefault_hreflang'], 20);
        
        // Rewrite Rules
        add_action('init', [$this, 'add_property_rewrite_rules']);
        
        // Admin
        add_action('admin_init', [$this, 'disable_comments_admin']);
        add_action('admin_menu', [$this, 'remove_comments_menu']);
        add_action('init', [$this, 'remove_comments_admin_bar']);
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        add_filter('comments_array', '__return_empty_array', 10, 2);
        
        // ACF
        add_action('init', [$this, 'add_acf_options_page']);
        
        // Author Fields
        add_action('show_user_profile', [$this, 'add_author_phone_field']);
        add_action('edit_user_profile', [$this, 'add_author_phone_field']);
        add_action('personal_options_update', [$this, 'save_author_phone_field']);
        add_action('edit_user_profile_update', [$this, 'save_author_phone_field']);
        add_filter('wpseo_schema_person', [$this, 'modify_person_to_author'], 11, 2);
    }

    /**
     * Add real estate schema to Yoast JSON-LD
     */
    public function add_real_estate_schema_to_yoast($data) {
        if (is_singular('properties')) {
            global $post;

            $property_schema = [
                "@context" => "https://schema.org",
                "@type" => "RealEstateListing",
                "@id" => get_permalink() . "#realestate",
                "mainEntityOfPage" => [
                    "@id" => get_permalink() . "#webpage"
                ],
                "name" => get_the_title(),
                "url" => get_permalink(),
                "image" => get_the_post_thumbnail_url(),
                "description" => get_the_excerpt(),
                "address" => [
                    "@type" => "PostalAddress",
                    "streetAddress" => get_post_meta($post->ID, 'property_address', true),
                    "addressLocality" => get_post_meta($post->ID, 'property_city', true),
                    "addressRegion" => "QC",
                    "postalCode" => get_post_meta($post->ID, 'property_postal_code', true),
                    "addressCountry" => "CA"
                ],
                "geo" => [
                    "@type" => "GeoCoordinates",
                    "latitude" => get_post_meta($post->ID, 'property_latitude', true),
                    "longitude" => get_post_meta($post->ID, 'property_longitude', true)
                ],
                "offers" => [
                    "@type" => "Offer",
                    "price" => get_post_meta($post->ID, 'property_price', true),
                    "priceCurrency" => "CAD",
                    "availability" => "https://schema.org/InStock"
                ]
            ];

            $data['@graph'][] = $property_schema;
        }

        return $data;
    }

    /**
     * Handle multilingual property URLs (hreflang tags)
     */
    public function fix_property_hreflang_tags() {
        if (get_post_type() !== 'properties') {
            return;
        }

        // Stop WPML's own hreflang tags
        remove_action('wp_head', ['WPML_SEO_HeadLangs', 'head_langs']);

        $base_url = get_permalink();
        if (!$base_url) return;

        $langs = [
            'en' => 'en-CA',
            'fr' => 'fr-CA',
        ];

        foreach ($langs as $code => $hreflang) {
            $url = $code === 'fr' ? $base_url : str_replace('/properties/', '/en/properties/', $base_url);
            printf('<link rel="alternate" hreflang="%s" href="%s" />' . "\n", $hreflang, esc_url($url));
        }

        // Add x-default without /x-default/ in the URL
        printf('<link rel="alternate" href="%s" hreflang="x-default" />' . "\n", esc_url($base_url));
    }

    /**
     * Add rewrite rules for property URLs
     */
    public function add_property_rewrite_rules() {
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

    /**
     * Add ACF options page
     */
    public function add_acf_options_page() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page('Theme Options');
        }
    }

    /**
     * Disable comments in admin
     */
    public function disable_comments_admin() {
        global $pagenow;
        
        if ($pagenow === 'edit-comments.php') {
            wp_safe_redirect(admin_url());
            exit;
        }

        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    /**
     * Remove comments menu
     */
    public function remove_comments_menu() {
        remove_menu_page('edit-comments.php');
    }

    /**
     * Remove comments from admin bar
     */
    public function remove_comments_admin_bar() {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }

    /**
     * Add aggregate ratings to Yoast JSON
     */
    public function add_aggregate_ratings($data) {
        if (!is_front_page()) return $data;

        foreach ($data['@graph'] as &$node) {
            if (
                isset($node['@type']) &&
                in_array('Organization', (array) $node['@type'], true) &&
                $node['@id'] === 'https://peterthompson.ca/#organization'
            ) {
                $node['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '5.0',
                    'reviewCount' => '115'
                ];
            }
        }

        return $data;
    }

    /**
     * Fix canonical URL for single property pages
     */
    public function fix_property_canonical($canonical) {
        global $result_obj;
        if (get_query_var('mls') && isset($result_obj[$_GET['mls']]['property'])) {
            return $this->get_property_permalink((object)$result_obj[$_GET['mls']]['property']);
        }
        return $canonical;
    }

    /**
     * Output property canonical URL
     */
    public function output_property_canonical() {
        if (get_query_var('mls') && isset($GLOBALS['result_obj'][$_GET['mls']]['property'])) {
            $canonical = $this->get_property_permalink((object)$GLOBALS['result_obj'][$_GET['mls']]['property']);
            echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . PHP_EOL;
        }
    }

    /**
     * Remove WPML's x-default hreflang for property pages
     */
    public function remove_xdefault_hreflang($hreflangs) {
        if (is_singular('properties') || is_post_type_archive('properties')) {
            unset($hreflangs['x-default']);
        }
        return $hreflangs;
    }

    /**
     * Get property permalink
     */
    public function get_property_permalink($property) {
        $address = $property->civic_number_start . ' ' . $property->street_name;
        $slug = urlencode($address);
        return home_url("/properties/{$slug}/{$property->mls_no}/");
    }

    /**
     * Add author schema
     */
    public function modify_person_to_author($data, $context) {
        if (!empty($data['@type'])) {
            $data['@type'] = ['Author', 'Person'];
        }
        return $data;
    }

    /**
     * Add the custom phone number field to the user profile page
     */
    public function add_author_phone_field($user) { ?>
        <h3>Contact Information</h3>
        <table class="form-table">
            <tr>
                <th><label for="author_phone">Phone Number</label></th>
                <td>
                    <input type="text" name="author_phone" id="author_phone" 
                           value="<?php echo esc_attr(get_the_author_meta('author_phone', $user->ID)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
        </table>
    <?php }

    /**
     * Save the custom phone number field
     */
    public function save_author_phone_field($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        update_user_meta($user_id, 'author_phone', sanitize_text_field($_POST['author_phone']));
    }
}