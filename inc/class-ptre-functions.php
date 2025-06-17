<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization,
 * admin-specific hooks, and public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package PTRE_Plugin
 */
class Ptre_Functions {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @access   protected
     * @var      Ptre_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        error_log( 'PTRE_Functions: __construct() called.' ); // Debugging
        $this->plugin_name = 'ptre-plugin';
        $this->version = PTRE_PLUGIN_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ptre_Loader. Orchestrates the hooks of the plugin.
     * - Ptre_i18n. Defines internationalization functionality.
     * - Ptre_Admin. Defines all hooks for the admin area.
     * - Ptre_Public. Defines all hooks for the public side of the site.
     *
     * @access   private
     * @since    1.0.0
     */
    private function load_dependencies() {
        // Corrected paths relative to the plugin root
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ptre-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ptre-i18n.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ptre-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ptre-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ptre-custom-post-types.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ptre-api-handler.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ptre-blocks.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/blocks/hero-section-block.php'; // Also ensure this path is correct

        $this->loader = new Ptre_Loader();
    }

    /**
     * Define the hooks and the callback functions that are used for
     * setting up the admin area.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Ptre_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

    /**
     * Define the hooks and the callback functions that are used for
     * setting up the public-facing side of the site.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Ptre_Public( $this->get_plugin_name(), $this->get_version() );
        $plugin_blocks = new Ptre_Blocks( $this->get_plugin_name(), $this->get_version() ); // Instantiate Ptre_Blocks

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'acf/init', $plugin_blocks, 'register_acf_blocks' ); // Add ACF blocks registration to loader

        // Load MW Properties shortcode overrides from the plugin
        $this->loader->add_action('plugins_loaded', $this, 'load_mw_properties_shortcodes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of WordPress and
     * to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Load MW Properties shortcode overrides from the plugin.
     *
     * @since 1.0.0
     */
    public function load_mw_properties_shortcodes() {
        // Corrected path for shortcodes relative to plugin root
        $plugin_shortcode_path = plugin_dir_path( dirname( __FILE__ ) ) . 'mw-properties-templates/shortcodes/';
        if (file_exists($plugin_shortcode_path . 'nearby-cities.php')) {
            require_once $plugin_shortcode_path . 'nearby-cities.php';
        }
        // Add other shortcode overrides here if they exist in the plugin
    }
}