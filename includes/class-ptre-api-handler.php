<?php
/**
 * Handles all API communications with the property management system
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTRE_API_Handler {
    private $api_endpoint;
    private $api_key;
    private $cache_timeout = 3600; // 1 hour cache

    public function __construct() {
        $this->api_endpoint = get_option('ptre_api_endpoint');
        $this->api_key = get_option('ptre_api_key');
        
        add_action('init', [$this, 'register_settings']);
        add_action('wp_ajax_ptre_refresh_properties', [$this, 'refresh_properties_cache']);
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('ptre_options', 'ptre_api_endpoint');
        register_setting('ptre_options', 'ptre_api_key');
    }

    /**
     * Get properties from cache or API
     */
    public function get_properties($params = []) {
        $cache_key = 'ptre_properties_' . md5(serialize($params));
        $properties = get_transient($cache_key);

        if (false === $properties) {
            $properties = $this->fetch_from_api('/properties', $params);
            set_transient($cache_key, $properties, $this->cache_timeout);
        }

        return $properties;
    }

    /**
     * Get properties count from API
     */
    public function get_properties_count($params = []) {
        $cache_key = 'ptre_properties_count_' . md5(serialize($params));
        $count = get_transient($cache_key);

        if (false === $count) {
            $response = $this->fetch_from_api('/properties/count', $params);
            $count = $response['results_count'] ?? 0;
            set_transient($cache_key, $count, $this->cache_timeout);
        }

        return $count;
    }

    /**
     * Make API request
     */
    private function fetch_from_api($endpoint, $params = []) {
        $url = $this->api_endpoint . $endpoint;
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($params)
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            error_log('PTRE API Error: ' . $response->get_error_message());
            return false;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * Manually refresh properties cache
     */
    public function refresh_properties_cache() {
        check_ajax_referer('ptre_nonce', 'security');
        
        $params = isset($_POST['params']) ? $_POST['params'] : [];
        $cache_key = 'ptre_properties_' . md5(serialize($params));
        delete_transient($cache_key);
        
        wp_send_json_success(['message' => 'Cache refreshed']);
    }
}