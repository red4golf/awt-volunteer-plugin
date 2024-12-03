<?php

class AWT_Pilot_Handler {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function save_pilot_registration($data, $user_id) {
        try {
            $this->wpdb->query('START TRANSACTION');

            // Insert volunteer record
            $volunteer_data = [
                'user_id' => $user_id,
                'volunteer_type' => 'pilot',
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

            // Insert pilot details
            $pilot_data = [
                'volunteer_id' => $volunteer_id,
                'home_airport' => sanitize_text_field($data['pilotDetails']['homeAirport']),
                'secondary_airports' => json_encode($data['pilotDetails']['secondaryAirports']),
                'max_flight_hours' => intval($data['pilotDetails']['maxFlightHours']),
                'willing_distance' => intval($data['pilotDetails']['willingDistance']),
                'ifr_certified' => (bool)$data['pilotDetails']['ifrCertified'],
                'night_qualified' => (bool)$data['pilotDetails']['nightQualified']
            ];

            $this->wpdb->insert(
                $this->wpdb->prefix . 'awt_pilot_details',
                $pilot_data
            );
            $pilot_id = $this->wpdb->insert_id;

            // Insert aircraft records
            foreach ($data['aircraft'] as $aircraft) {
                $aircraft_data = [
                    'pilot_id' => $pilot_id,
                    'aircraft_type' => sanitize_text_field($aircraft['aircraftType']),
                    'tail_number' => sanitize_text_field($aircraft['tailNumber']),
                    'useful_load' => floatval($aircraft['usefulLoad']),
                    'range_nm' => intval($aircraft['rangeNM']),
                    'ownership_type' => sanitize_text_field($aircraft['ownershipType']),
                    'insurance_provider' => sanitize_text_field($aircraft['insuranceProvider']),
                    'insurance_policy_number' => sanitize_text_field($aircraft['insurancePolicyNumber']),
                    'insurance_expiration' => sanitize_text_field($aircraft['insuranceExpiration']),
                    'notes' => sanitize_textarea_field($aircraft['notes'])
                ];

                $this->wpdb->insert(
                    $this->wpdb->prefix . 'awt_aircraft',
                    $aircraft_data
                );
            }

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
