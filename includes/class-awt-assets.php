<?php

class AWT_Assets {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        // React app bundle
        wp_enqueue_script(
            'awt-volunteer-app',
            plugins_url('dist/bundle.js', dirname(__FILE__)),
            array(),
            filemtime(plugin_dir_path(dirname(__FILE__)) . 'dist/bundle.js'),
            true
        );

        // Localized data
        wp_localize_script('awt-volunteer-app', 'awt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'pilot_nonce' => wp_create_nonce('awt_pilot_registration'),
            'ground_nonce' => wp_create_nonce('awt_ground_registration')
        ));

        // Tailwind CSS (consider using a bundled version in production)
        wp_enqueue_style(
            'awt-tailwind',
            'https://cdn.tailwindcss.com',
            array(),
            null
        );
    }
}