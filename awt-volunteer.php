<?php
/**
 * Plugin Name: AWT Volunteer Management
 * Description: Volunteer management system for Angels Wings Transportation
 * Version: 1.0.0
 * Author: Your Name
 */

defined('ABSPATH') or die('Direct access not allowed');

require_once plugin_dir_path(__FILE__) . 'includes/class-awt-volunteer-activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-pilot-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-ground-transport-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-foster-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-administrative-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-ajax-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-awt-assets.php';

class AWT_Volunteer {
    private $ajax_handler;
    private $assets;
    private $pilot_handler;
    private $ground_handler;
    private $foster_handler;
    private $admin_handler;

    public function __construct() {
        register_activation_hook(__FILE__, array('AWT_Volunteer_Activator', 'activate'));
        
        $this->init_handlers();
        $this->register_shortcodes();
    }

    private function init_handlers() {
        $this->ajax_handler = new AWT_Ajax_Handler();
        $this->assets = new AWT_Assets();
        $this->pilot_handler = new AWT_Pilot_Handler();
        $this->ground_handler = new AWT_Ground_Transport_Handler();
        $this->foster_handler = new AWT_Foster_Handler();
        $this->admin_handler = new AWT_Administrative_Handler();
    }

    private function register_shortcodes() {
        add_shortcode('awt_volunteer_form', array($this, 'render_volunteer_form'));
    }

    public function render_volunteer_form($atts) {
        // Load React app container
        return '<div id="awt-volunteer-form"></div>';
    }
}

$awt_volunteer = new AWT_Volunteer();