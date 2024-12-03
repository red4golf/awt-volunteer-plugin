<?php

class AWT_Foster_Handler {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function save_foster_registration($data, $user_id) {
        try {
            $this->wpdb->query('START TRANSACTION');

            // Insert base volunteer record
            $volunteer_data = [
                'user_id' => $user_id,
                'volunteer_type' => 'foster',
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

            // Insert foster-specific details
            $foster_data = [
                'volunteer_id' => $volunteer_id,
                'housing_type' => sanitize_text_field($data['fosterDetails']['housingType']),
                'has_yard' => (bool)$data['fosterDetails']['hasYard'],
                'yard_details' => sanitize_textarea_field($data['fosterDetails']['yardDetails']),
                'existing_pets' => sanitize_textarea_field($data['fosterDetails']['existingPets']),
                'foster_experience' => sanitize_textarea_field($data['fosterDetails']['fosterExperience']),
                'willing_species' => json_encode($data['fosterDetails']['willingSpecies']),
                'max_animals' => intval($data['fosterDetails']['maxAnimals']),
                'available_space' => sanitize_text_field($data['fosterDetails']['availableSpace']),
                'local_vet_name' => sanitize_text_field($data['fosterDetails']['localVetName']),
                'local_vet_phone' => sanitize_text_field($data['fosterDetails']['localVetPhone']),
                'emergency_contact_name' => sanitize_text_field($data['fosterDetails']['emergencyContactName']),
                'emergency_contact_phone' => sanitize_text_field($data['fosterDetails']['emergencyContactPhone']),
                'household_members' => json_encode($data['fosterDetails']['householdMembers']),
                'housing_restrictions' => sanitize_textarea_field($data['fosterDetails']['housingRestrictions']),
                'preferred_animal_size' => json_encode($data['fosterDetails']['preferredAnimalSize']),
                'can_administer_meds' => (bool)$data['fosterDetails']['canAdministerMeds']
            ];

            $this->wpdb->insert(
                $this->wpdb->prefix . 'awt_foster_details',
                $foster_data
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

    public function get_foster_details($volunteer_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}awt_foster_details WHERE volunteer_id = %d",
            $volunteer_id
        );
        
        return $this->wpdb->get_row($query);
    }

    public function update_foster_status($volunteer_id, $status) {
        return $this->wpdb->update(
            $this->wpdb->prefix . 'awt_volunteers',
            ['status' => sanitize_text_field($status)],
            ['id' => $volunteer_id],
            ['%s'],
            ['%d']
        );
    }
}