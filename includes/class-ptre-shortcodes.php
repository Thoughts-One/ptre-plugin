<?php
/**
 * Handles legacy shortcode functionality during transition to blocks
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTRE_Shortcodes {
    private $api_handler;

    public function __construct() {
        $this->api_handler = new PTRE_API_Handler();
        
        // Register legacy shortcodes
        add_shortcode('ptre_properties', [$this, 'render_properties']);
        add_shortcode('ptre_featured_properties', [$this, 'render_featured_properties']);
    }

    /**
     * Render properties grid shortcode
     */
    public function render_properties($atts) {
        $atts = shortcode_atts([
            'limit' => 12,
            'type' => '',
            'location' => ''
        ], $atts);

        $properties = $this->api_handler->get_properties([
            'limit' => $atts['limit'],
            'type' => $atts['type'],
            'location' => $atts['location']
        ]);

        if (empty($properties)) {
            return '<p>No properties found.</p>';
        }

        ob_start();
        ?>
        <div class="ptre-properties-grid">
            <?php foreach ($properties as $property): ?>
                <div class="ptre-property">
                    <h3><?php echo esc_html($property['title']); ?></h3>
                    <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                    <p><?php echo esc_html($property['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render featured properties shortcode
     */
    public function render_featured_properties($atts) {
        $atts = shortcode_atts([
            'limit' => 3
        ], $atts);

        $properties = $this->api_handler->get_properties([
            'limit' => $atts['limit'],
            'featured' => true
        ]);

        if (empty($properties)) {
            return '<p>No featured properties found.</p>';
        }

        ob_start();
        ?>
        <div class="ptre-featured-properties">
            <?php foreach ($properties as $property): ?>
                <div class="ptre-featured-property">
                    <h3><?php echo esc_html($property['title']); ?></h3>
                    <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                    <p><?php echo esc_html($property['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}