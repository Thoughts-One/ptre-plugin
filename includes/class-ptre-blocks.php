<?php
/**
 * Handles Gutenberg blocks for property display
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTRE_Blocks {
    private $api_handler;

    public function __construct() {
        $this->api_handler = new PTRE_API_Handler();
        
        add_action('init', [$this, 'register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_assets']);
    }

    /**
     * Register custom blocks
     */
    public function register_blocks() {
        register_block_type('ptre/properties-grid', [
            'render_callback' => [$this, 'render_properties_grid'],
            'attributes' => [
                'limit' => [
                    'type' => 'number',
                    'default' => 12
                ],
                'propertyType' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'location' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'status' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'searchQuery' => [
                    'type' => 'string',
                    'default' => ''
                ]
            ]
        ]);

        register_block_type('ptre/featured-properties', [
            'render_callback' => [$this, 'render_featured_properties'],
            'attributes' => [
                'limit' => [
                    'type' => 'number',
                    'default' => 3
                ]
            ]
        ]);

        register_block_type('ptre/city-properties', [
            'render_callback' => [$this, 'render_city_properties'],
            'attributes' => [
                'status' => [
                    'type' => 'string',
                    'default' => 'EV'
                ],
                'municipalityCode' => [
                    'type' => 'string',
                    'default' => ''
                ]
            ]
        ]);

        register_block_type('ptre/agent-properties', [
            'render_callback' => [$this, 'render_agent_properties'],
            'attributes' => [
                'agentCode' => [
                    'type' => 'string',
                    'default' => ''
                ]
            ]
        ]);
    }

    /**
     * Render agent properties block
     */
    public function render_agent_properties($attributes) {
        if (empty($attributes['agentCode'])) {
            return '';
        }

        $params = [
            'agents' => $attributes['agentCode'],
            'status' => 'EV',
            'order' => 'price',
            'dir' => 'desc'
        ];

        // Get property count first
        $count = $this->api_handler->get_properties_count($params);

        if ($count === 0) {
            return ''; // Don't render anything if no properties
        }

        $properties = $this->api_handler->get_properties($params);

        ob_start();
        ?>
        <section class="explore member--listings padd-lg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2><?= ICL_LANGUAGE_CODE === 'en' ? 'My Listings' : 'Mes Propriétés'; ?></h2>
                    </div>
                </div>
                <div class="row mt-4">
                    <?php foreach ($properties as $property): ?>
                        <div class="col">
                            <div class="ptre-property">
                                <h3><?php echo esc_html($property['title']); ?></h3>
                                <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                                <p><?php echo esc_html($property['description']); ?></p>
                                <p><?php echo esc_html($property['price']); ?></p>
                                <a href="<?php echo esc_url($property['url']); ?>" class="wp-block-button__link">
                                    <?php echo ICL_LANGUAGE_CODE === 'en' ? 'View Details' : 'Voir les détails'; ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render city properties block
     */
    public function render_city_properties($attributes) {
        $params = [
            'status' => $attributes['status'],
            'municipality_code' => $attributes['municipalityCode'],
            'order' => 'price',
            'dir' => 'desc'
        ];

        // Get property count first
        $count = $this->api_handler->get_properties_count($params);

        if ($count === 0) {
            return ''; // Don't render anything if no properties
        }

        $title = $attributes['status'] === 'EV'
            ? get_field('for_sale_title', 'option')
            : get_field('for_sold_title', 'option');

        $properties = $this->api_handler->get_properties($params);

        ob_start();
        ?>
        <section class="explore properties-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2><b><?php echo esc_html($title); ?> <?php the_title(); ?></b></h2>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 mt-3">
                    <?php foreach ($properties as $property): ?>
                        <div class="col">
                            <div class="ptre-property">
                                <h3><?php echo esc_html($property['title']); ?></h3>
                                <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                                <p><?php echo esc_html($property['description']); ?></p>
                                <p><?php echo esc_html($property['price']); ?></p>
                                <a href="<?php echo esc_url($property['url']); ?>" class="wp-block-button__link">
                                    <?php echo ICL_LANGUAGE_CODE === 'en' ? 'View Details' : 'Voir les détails'; ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_assets() {
        wp_enqueue_script(
            'ptre-blocks',
            plugins_url('assets/js/blocks.js', PTRE_PLUGIN_DIR . 'ptre-plugin.php'),
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'],
            filemtime(PTRE_PLUGIN_DIR . 'assets/js/blocks.js')
        );

        wp_enqueue_style(
            'ptre-blocks',
            plugins_url('assets/css/blocks.css', PTRE_PLUGIN_DIR . 'ptre-plugin.php'),
            [],
            filemtime(PTRE_PLUGIN_DIR . 'assets/css/blocks.css')
        );
    }

    /**
     * Render properties grid block
     */
    public function render_properties_grid($attributes) {
        $params = [
            'limit' => $attributes['limit'],
            'type' => $attributes['propertyType'],
            'location' => $attributes['location'],
            'q' => $attributes['searchQuery']
        ];

        // Handle status filters
        if ($attributes['status'] === 'open') {
            $params['open_house'] = true;
        } elseif ($attributes['status'] === 'sold') {
            $params['status'] = 'VE';
        } elseif ($attributes['status'] === 'rent') {
            $params['status'] = 'EV';
            $params['min_price_rent'] = 100;
        } elseif ($attributes['status'] === 'sale') {
            $params['status'] = 'EV';
            $params['min_price'] = 10000;
        }

        $properties = $this->api_handler->get_properties($params);

        if (empty($properties)) {
            return '<p>' . (ICL_LANGUAGE_CODE === 'en' ? 'No properties found.' : 'Aucune propriété trouvée.') . '</p>';
        }

        ob_start();
        ?>
        <div class="ptre-properties-grid wp-block-ptre-properties-grid">
            <?php foreach ($properties as $property): ?>
                <div class="ptre-property">
                    <h3><?php echo esc_html($property['title']); ?></h3>
                    <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                    <p><?php echo esc_html($property['description']); ?></p>
                    <a href="<?php echo esc_url($property['url']); ?>" class="wp-block-button__link">
                        <?php echo ICL_LANGUAGE_CODE === 'en' ? 'View Details' : 'Voir les détails'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render featured properties block
     */
    public function render_featured_properties($attributes) {
        $properties = $this->api_handler->get_properties([
            'limit' => $attributes['limit'],
            'featured' => true
        ]);

        if (empty($properties)) {
            return '<p>' . (ICL_LANGUAGE_CODE === 'en' ? 'No featured properties found.' : 'Aucune propriété vedette trouvée.') . '</p>';
        }

        ob_start();
        ?>
        <div class="ptre-featured-properties wp-block-ptre-featured-properties">
            <?php foreach ($properties as $property): ?>
                <div class="ptre-featured-property">
                    <h3><?php echo esc_html($property['title']); ?></h3>
                    <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>">
                    <p><?php echo esc_html($property['description']); ?></p>
                    <a href="<?php echo esc_url($property['url']); ?>" class="wp-block-button__link">
                        <?php echo ICL_LANGUAGE_CODE === 'en' ? 'View Details' : 'Voir les détails'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}