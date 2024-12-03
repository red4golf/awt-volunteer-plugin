<?php

class AWT_Ground_Transport_Handler {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function save_ground_transport_registration($data, $user_id) {
        try {
            $this->wpdb->query('START TRANSACTION');

            $volunteer_data = [
                'user_id' => $user_id,
                'volunteer_type' => 'ground_transport',
                'status' => 'pending',
                'first_name' => sanitize_text_field($data['firstName']),
                'last_name' => sanitize_text_field($data['lastName']),
                'phone' => sanitize_text_field($data['phone'])
            ];

            $this->wpdb->insert(
                $this->wpdb->prefix . 'awt_volunteers',
                $volunteer_data
            );
            $volunteer_id = $this->wpdb->insert_id;

            $ground_transport_data = [
                'volunteer_id' => $volunteer_id,
                'vehicle_make' => sanitize_text_field($data['vehicleMake']),
                'vehicle_model' => sanitize_text_field($data['vehicleModel']),
                'vehicle_year' => intval($data['vehicleYear']),
                'vehicle_type' => sanitize_text_field($data['vehicleType']),
                'max_cargo_weight' => floatval($data['maxCargoWeight']),
                'cargo_space_dimensions' => sanitize_text_field($data['cargo_space_dimensions']),
                'climate_control' => (bool)$data['climateControl'],
                'willing_distance' => intval($data['willingDistance']),
                'has_carrier' => (bool)$data['hasCarrier'],
                'carrier_details' => $data['hasCarrier'] ? sanitize_textarea_field($data['carrierDetails']) : '',
                'insurance_provider' => sanitize_text_field($data['insuranceProvider']),
                'insurance_policy_number' => sanitize_text_field($data['insurancePolicyNumber']),
                'insurance_expiration' => sanitize_text_field($data['insuranceExpiration'])
            ];

            $this->wpdb->insert(
                $this->wpdb->prefix . 'awt_ground_transport_details',
                $ground_transport_data
            );

            $this->wpdb->query('COMMIT');
            return [
                'success' => true,
                'volunteer_id' => $volunteer_id
            ];

        } catch (Exception $e) {
            $this->wpdb->query('ROLLBACK');
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
