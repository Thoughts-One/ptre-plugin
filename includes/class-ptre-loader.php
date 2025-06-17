<?php
/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Ptre_Plugin
 * @subpackage Ptre_Plugin/includes
 */
class Ptre_Loader {

    protected $actions;
    protected $filters;

    public function __construct() {
        error_log( 'PTRE_Loader: __construct() called.' ); // Debugging
        $this->actions = array();
        $this->filters = array();
    }

    public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->actions = $this->add_hook( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
    }

    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->filters = $this->add_hook( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
    }

    private function add_hook( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );
        return $hooks;
    }

    public function run() {
        error_log( 'PTRE_Loader: run() called. Registering ' . count( $this->filters ) . ' filters and ' . count( $this->actions ) . ' actions.' ); // Debugging
        foreach ( $this->filters as $hook ) {
            add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
        }
        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
        }
    }
}