<?php  
/**  
 * Plugin Name: PTRE Plugin  
 * Plugin URI:  https://peterthompson.ca  
 * Description: Companion plugin for the PTRE Theme, handling custom post types, taxonomies, shortcodes, and other non-theme-specific functionalities.  
 * Version:     1.0.0  
 * Author:      Marketing Websites  
 * Author URI:  https://marketingwebsites.ca  
 * License:     GPL-2.0+  
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt  
 * Text Domain: ptre-plugin  
 * Domain Path: /languages  
 *  
 * @package PTRE_Plugin  
 */  
  
// If this file is called directly, abort.  
if ( ! defined( 'WPINC' ) ) {  
    die;  
}  
  
/**  
 * Current plugin version.  
 */  
define( 'PTRE_PLUGIN_VERSION', '1.0.0' );  
  
/**  
 * The core plugin class that is used to define internationalization,  
 * admin-specific hooks, and public-facing hooks.  
 */  
require plugin_dir_path( __FILE__ ) . 'inc/class-ptre-functions.php'; // Corrected path  
  
/**  
 * Begins execution of the plugin.  
 *  
 * Since everything within the plugin is registered via hooks,  
 * then kicking off the plugin from this point in the file means  
 * that all of the hooks will be defined.  
 */  
function run_ptre_plugin() {  
    $plugin = new Ptre_Functions();  
    $plugin->run();  
}  
run_ptre_plugin();