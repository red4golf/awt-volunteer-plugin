<?php
/**
 * Fired during plugin activation
 * @package AWT_Volunteer
 */
class AWT_Volunteer_Activator {
    public static function activate() {
        self::create_database_tables();
        self::create_roles_and_capabilities();
    }

    private static function create_database_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Core volunteer table
        $table_volunteers = $wpdb->prefix . 'awt_volunteers';
        $sql_volunteers = "CREATE TABLE IF NOT EXISTS $table_volunteers (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            volunteer_type VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            emergency_contact_name VARCHAR(100),
            emergency_contact_phone VARCHAR(20),
            address_line1 VARCHAR(100),
            address_line2 VARCHAR(100),
            city VARCHAR(50),
            state VARCHAR(2),
            zip VARCHAR(10),
            availability_json JSON,
            skills_json JSON,
            background_check_status VARCHAR(20) DEFAULT 'pending',
            background_check_date DATE,
            training_status VARCHAR(20) DEFAULT 'not_started',
            training_completed_date DATE,
            last_active_date DATE,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY volunteer_type (volunteer_type),
            KEY status (status)
        ) $charset_collate;";

        // Pilot details
        $table_pilot_details = $wpdb->prefix . 'awt_pilot_details';
        $sql_pilot_details = "CREATE TABLE IF NOT EXISTS $table_pilot_details (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            home_airport VARCHAR(10) NOT NULL,
            secondary_airports JSON,
            max_flight_hours INTEGER,
            willing_distance INTEGER,
            ifr_certified BOOLEAN DEFAULT FALSE,
            night_qualified BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Aircraft details
        $table_aircraft = $wpdb->prefix . 'awt_aircraft';
        $sql_aircraft = "CREATE TABLE IF NOT EXISTS $table_aircraft (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            pilot_id mediumint(9) NOT NULL,
            aircraft_type VARCHAR(100) NOT NULL,
            tail_number VARCHAR(20),
            useful_load DECIMAL(7,2),
            range_nm INTEGER,
            ownership_type VARCHAR(50),
            insurance_provider VARCHAR(100),
            insurance_policy_number VARCHAR(100),
            insurance_expiration DATE,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY pilot_id (pilot_id),
            FOREIGN KEY (pilot_id) REFERENCES ${wpdb->prefix}awt_pilot_details(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Ground transport details
        $table_ground = $wpdb->prefix . 'awt_ground_transport_details';
        $sql_ground = "CREATE TABLE IF NOT EXISTS $table_ground (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            vehicle_make VARCHAR(50) NOT NULL,
            vehicle_model VARCHAR(50) NOT NULL,
            vehicle_year INTEGER,
            vehicle_type VARCHAR(50),
            max_cargo_weight DECIMAL(7,2),
            cargo_space_dimensions JSON,
            climate_control BOOLEAN DEFAULT FALSE,
            willing_distance INTEGER,
            has_carrier BOOLEAN DEFAULT FALSE,
            carrier_details TEXT,
            insurance_provider VARCHAR(100),
            insurance_policy_number VARCHAR(100),
            insurance_expiration DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Foster details table
        $table_foster = $wpdb->prefix . 'awt_foster_details';
        $sql_foster = "CREATE TABLE IF NOT EXISTS $table_foster (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            housing_type VARCHAR(50) NOT NULL,
            has_yard BOOLEAN DEFAULT FALSE,
            yard_details TEXT,
            existing_pets TEXT,
            foster_experience TEXT,
            willing_species JSON,
            max_animals INTEGER,
            available_space VARCHAR(100),
            local_vet_name VARCHAR(100),
            local_vet_phone VARCHAR(20),
            household_members JSON,
            housing_restrictions TEXT,
            preferred_animal_size JSON,
            can_administer_meds BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Administrative details table
        $table_admin = $wpdb->prefix . 'awt_administrative_details';
        $sql_admin = "CREATE TABLE IF NOT EXISTS $table_admin (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            skills JSON,
            preferred_roles JSON,
            availability_schedule JSON,
            can_work_remote BOOLEAN DEFAULT FALSE,
            time_zone VARCHAR(50),
            languages JSON,
            volunteer_experience TEXT,
            professional_background TEXT,
            computer_skills JSON,
            has_dispatch_experience BOOLEAN DEFAULT FALSE,
            dispatch_details TEXT,
            weekly_hours_available INTEGER,
            preferred_communication VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            KEY has_dispatch_experience (has_dispatch_experience),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Certifications table
        $table_certifications = $wpdb->prefix . 'awt_pilot_certifications';
        $sql_certifications = "CREATE TABLE IF NOT EXISTS $table_certifications (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            certificate_type VARCHAR(100) NOT NULL,
            certificate_number VARCHAR(100),
            issuing_authority VARCHAR(100),
            issue_date DATE,
            expiration_date DATE,
            document_url VARCHAR(255),
            verification_status VARCHAR(20) DEFAULT 'pending',
            verified_by bigint(20),
            verified_date DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Training records
        $table_training = $wpdb->prefix . 'awt_training_records';
        $sql_training = "CREATE TABLE IF NOT EXISTS $table_training (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            training_type VARCHAR(50) NOT NULL,
            status VARCHAR(20) DEFAULT 'not_started',
            completion_date DATETIME,
            certificate_url VARCHAR(255),
            expiration_date DATE,
            verified_by bigint(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            KEY training_type (training_type),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Service history
        $table_service = $wpdb->prefix . 'awt_service_history';
        $sql_service = "CREATE TABLE IF NOT EXISTS $table_service (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            volunteer_id mediumint(9) NOT NULL,
            service_type VARCHAR(50) NOT NULL,
            service_date DATE NOT NULL,
            hours_served DECIMAL(5,2),
            mission_id VARCHAR(50),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY volunteer_id (volunteer_id),
            KEY service_type (service_type),
            KEY service_date (service_date),
            FOREIGN KEY (volunteer_id) REFERENCES ${wpdb->prefix}awt_volunteers(id) ON DELETE CASCADE
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_volunteers);
        dbDelta($sql_pilot_details);
        dbDelta($sql_aircraft);
        dbDelta($sql_ground);
        dbDelta($sql_foster);
        dbDelta($sql_admin);
        dbDelta($sql_certifications);
        dbDelta($sql_training);
        dbDelta($sql_service);
    }

    private static function create_roles_and_capabilities() {
        add_role(
            'awt_volunteer',
            __('AWT Volunteer', 'awt-volunteer'),
            array(
                'read' => true,
                'upload_files' => true,
                'awt_view_own_profile' => true,
                'awt_update_availability' => true,
                'awt_view_assignments' => true
            )
        );

        add_role(
            'awt_coordinator',
            __('AWT Coordinator', 'awt-volunteer'),
            array(
                'read' => true,
                'upload_files' => true,
                'awt_view_volunteers' => true,
                'awt_assign_missions' => true,
                'awt_view_reports' => true,
                'awt_manage_availability' => true
            )
        );

        $admin_role = get_role('administrator');
        $admin_capabilities = array(
            'manage_awt_volunteers',
            'view_awt_volunteer_reports',
            'manage_awt_settings',
            'verify_awt_certifications', 
            'manage_awt_training',
            'manage_awt_missions',
            'delete_awt_records',
            'awt_manage_fosters',
            'awt_view_foster_applications',
            'awt_approve_foster_homes',
            'awt_manage_admin_volunteers',
            'awt_assign_admin_roles',
            'awt_manage_dispatch'
        );
        
        foreach ($admin_capabilities as $cap) {
            $admin_role->add_cap($cap);
        }

        // Add foster-specific capabilities to coordinator
        $coordinator_role = get_role('awt_coordinator');
        $foster_capabilities = array(
            'awt_manage_fosters',
            'awt_view_foster_applications',
            'awt_approve_foster_homes'
        );
        
        foreach ($foster_capabilities as $cap) {
            $coordinator_role->add_cap($cap);
        }
    }
}