<?php

class AWT_Administrative_Handler {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function save_administrative_registration($data, $user_id) {
        try {
            $this->wpdb->query('START TRANSACTION');

            // Insert base volunteer record
            $volunteer_data = [
                'user_id' => $user_id,
                'volunteer_type' => 'administrative',
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

            // Insert administrative-specific details
            $admin_data = [
                'volunteer_id' => $volunteer_id,
                'skills' => json_encode($data['adminDetails']['skills']),
                'preferred_roles' => json_encode($data['adminDetails']['preferredRoles']),
                'availability_schedule' => json_encode($data['adminDetails']['availabilitySchedule']),
                'can_work_remote' => (bool)$data['adminDetails']['canWorkRemote'],
                'time_zone' => sanitize_text_field($data['adminDetails']['timeZone']),
                'languages' => json_encode($data['adminDetails']['languages']),
                'volunteer_experience' => sanitize_textarea_field($data['adminDetails']['volunteerExperience']),
                'professional_background' => sanitize_textarea_field($data['adminDetails']['professionalBackground']),
                'computer_skills' => json_encode($data['adminDetails']['computerSkills']),
                'has_dispatch_experience' => (bool)$data['adminDetails']['hasDispatchExperience'],
                'dispatch_details' => sanitize_textarea_field($data['adminDetails']['dispatchDetails']),
                'weekly_hours_available' => intval($data['adminDetails']['weeklyHoursAvailable']),
                'preferred_communication' => sanitize_text_field($data['adminDetails']['preferredCommunication']),
                'emergency_contact_name' => sanitize_text_field($data['adminDetails']['emergencyContactName']),
                'emergency_contact_phone' => sanitize_text_field($data['adminDetails']['emergencyContactPhone'])
            ];

            $this->wpdb->insert(
                $this->wpdb->prefix . 'awt_administrative_details',
                $admin_data
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

    public function get_administrative_details($volunteer_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}awt_administrative_details WHERE volunteer_id = %d",
            $volunteer_id
        );
        
        return $this->wpdb->get_row($query);
    }

    public function update_administrative_status($volunteer_id, $status) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'awt_volunteers',
            ['status' => sanitize_text_field($status)],
            ['id' => $volunteer_id],
            ['%s'],
            ['%d']
        );
    }

    public function get_available_dispatch_volunteers() {
        $query = "
            SELECT v.*, ad.* 
            FROM {$this->wpdb->prefix}awt_volunteers v
            JOIN {$this->wpdb->prefix}awt_administrative_details ad ON v.id = ad.volunteer_id
            WHERE v.status = 'active'
            AND ad.has_dispatch_experience = 1
        ";
        
        return $this->wpdb->get_results($query);
    }
}