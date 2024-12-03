<?php
/**
 * The admin-specific functionality of the plugin.
 */
class AWT_Volunteer_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/awt-volunteer-admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/awt-volunteer-admin.js',
            array('jquery'),
            $this->version,
            false
        );

        // Localize script for AJAX
        wp_localize_script(
            $this->plugin_name,
            'awtVolunteerAdmin',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('awt_volunteer_admin_nonce')
            )
        );
    }

    /**
     * Add menu items to the admin area
     */
    public function add_admin_menu() {
        // Main menu item
        add_menu_page(
            __('AWT Volunteers', 'awt-volunteer'),
            __('AWT Volunteers', 'awt-volunteer'),
            'manage_awt_volunteers',
            'awt-volunteers',
            array($this, 'display_volunteer_dashboard'),
            'dashicons-groups',
            30
        );

        // Submenu items
        add_submenu_page(
            'awt-volunteers',
            __('Dashboard', 'awt-volunteer'),
            __('Dashboard', 'awt-volunteer'),
            'manage_awt_volunteers',
            'awt-volunteers'
        );

        add_submenu_page(
            'awt-volunteers',
            __('All Volunteers', 'awt-volunteer'),
            __('All Volunteers', 'awt-volunteer'),
            'manage_awt_volunteers',
            'awt-volunteer-list',
            array($this, 'display_volunteer_list')
        );

        add_submenu_page(
            'awt-volunteers',
            __('Applications', 'awt-volunteer'),
            __('Applications', 'awt-volunteer'),
            'manage_awt_volunteers',
            'awt-volunteer-applications',
            array($this, 'display_applications')
        );

        add_submenu_page(
            'awt-volunteers',
            __('Settings', 'awt-volunteer'),
            __('Settings', 'awt-volunteer'),
            'manage_awt_volunteers',
            'awt-volunteer-settings',
            array($this, 'display_settings')
        );
    }

    /**
     * Display the volunteer dashboard
     */
    public function display_volunteer_dashboard() {
        require_once plugin_dir_path(__FILE__) . 'partials/awt-volunteer-admin-dashboard.php';
    }

    /**
     * Display the volunteer list
     */
    public function display_volunteer_list() {
        require_once plugin_dir_path(__FILE__) . 'partials/awt-volunteer-admin-list.php';
    }

    /**
     * Display pending applications
     */
    public function display_applications() {
        require_once plugin_dir_path(__FILE__) . 'partials/awt-volunteer-admin-applications.php';
    }

    /**
     * Display plugin settings
     */
    public function display_settings() {
        require_once plugin_dir_path(__FILE__) . 'partials/awt-volunteer-admin-settings.php';
    }
}
