<?php

class AWT_Ajax_Handler {
    private $pilot_handler;
    private $ground_handler;

    public function __construct() {
        $this->pilot_handler = new AWT_Pilot_Handler();
        $this->ground_handler = new AWT_Ground_Transport_Handler();
        
        add_action('wp_ajax_submit_pilot_registration', array($this, 'handle_pilot_registration'));
        add_action('wp_ajax_submit_ground_registration', array($this, 'handle_ground_registration'));
    }

    public function handle_pilot_registration() {
        if (!check_ajax_referer('awt_pilot_registration', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
        }

        if (!is_user_logged_in()) {
            wp_send_json_error('User must be logged in');
        }

        $data = json_decode(stripslashes($_POST['data']), true);
        $user_id = get_current_user_id();
        
        $result = $this->pilot_handler->save_pilot_registration($data, $user_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result['error']);
        }
    }

    public function handle_ground_registration() {
        if (!check_ajax_referer('awt_ground_registration', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
        }

        if (!is_user_logged_in()) {
            wp_send_json_error('User must be logged in');
        }

        $data = json_decode(stripslashes($_POST['data']), true);
        $user_id = get_current_user_id();
        
        $result = $this->ground_handler->save_ground_transport_registration($data, $user_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result['error']);
        }
    }
}
