<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class AWT_Volunteer_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'volunteer',
            'plural'   => 'volunteers',
            'ajax'     => false
        ));
    }

    public function get_columns() {
        return array(
            'cb'              => '<input type="checkbox" />',
            'name'            => __('Name', 'awt-volunteer'),
            'volunteer_type'  => __('Type', 'awt-volunteer'),
            'status'         => __('Status', 'awt-volunteer'),
            'last_active'    => __('Last Active', 'awt-volunteer'),
            'missions'       => __('Missions', 'awt-volunteer'),
            'certifications' => __('Certifications', 'awt-volunteer'),
            'background'     => __('Background Check', 'awt-volunteer'),
            'actions'        => __('Actions', 'awt-volunteer')
        );
    }

    public function get_sortable_columns() {
        return array(
            'name'         => array('name', true),
            'volunteer_type' => array('volunteer_type', false),
            'status'      => array('status', false),
            'last_active' => array('last_active', false)
        );
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        // Get data and set pagination
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = $this->get_total_volunteers();

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $this->items = $this->get_volunteers($per_page, $current_page);
    }

    private function get_volunteers($per_page = 20, $page_number = 1) {
        global $wpdb;

        $sql = "SELECT v.*, u.display_name, u.user_email 
                FROM {$wpdb->prefix}awt_volunteers v
                LEFT JOIN {$wpdb->users} u ON v.user_id = u.ID";

        // Handle search
        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $sql .= " WHERE u.display_name LIKE '%$search%' 
                      OR u.user_email LIKE '%$search%'";
        }

        // Handle filters
        if (!empty($_REQUEST['volunteer_type'])) {
            $type = esc_sql($_REQUEST['volunteer_type']);
            $sql .= " WHERE v.volunteer_type = '$type'";
        }

        if (!empty($_REQUEST['status'])) {
            $status = esc_sql($_REQUEST['status']);
            $sql .= " WHERE v.status = '$status'";
        }

        // Handle sorting
        $orderby = (!empty($_REQUEST['orderby'])) ? esc_sql($_REQUEST['orderby']) : 'created_at';
        $order = (!empty($_REQUEST['order'])) ? esc_sql($_REQUEST['order']) : 'DESC';
        $sql .= " ORDER BY $orderby $order";

        // Handle pagination
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        return $wpdb->get_results($sql, ARRAY_A);
    }

    private function get_total_volunteers() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}awt_volunteers");
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'volunteer_type':
                return $this->get_volunteer_type_label($item['volunteer_type']);
            case 'status':
                return $this->get_status_badge($item['status']);
            case 'last_active':
                return $item['last_active_date'] ? date('Y-m-d', strtotime($item['last_active_date'])) : '-';
            case 'missions':
                return $this->get_mission_count($item['id']);
            case 'certifications':
                return $this->get_certification_status($item['id']);
            case 'background':
                return $this->get_background_check_status($item['background_check_date']);
            default:
                return print_r($item, true);
        }
    }

    public function column_name($item) {
        $actions = array(
            'edit'    => sprintf(
                '<a href="%s">%s</a>',
                admin_url('admin.php?page=awt-volunteer-applications&action=edit&id=' . $item['id']),
                __('Edit', 'awt-volunteer')
            ),
            'view'    => sprintf(
                '<a href="#" class="awt-view-volunteer" data-id="%s">%s</a>',
                $item['id'],
                __('View Details', 'awt-volunteer')
            ),
            'delete'  => sprintf(
                '<a href="%s" class="awt-delete-volunteer" data-id="%s">%s</a>',
                admin_url('admin.php?page=awt-volunteer-list&action=delete&id=' . $item['id']),
                $item['id'],
                __('Delete', 'awt-volunteer')
            )
        );

        return sprintf(
            '%1$s <span style="color:silver">(email: %2$s)</span>%3$s',
            $item['display_name'],
            $item['user_email'],
            $this->row_actions($actions)
        );
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="volunteer_id[]" value="%s" />',
            $item['id']
        );
    }

    private function get_volunteer_type_label($type) {
        $types = array(
            'pilot' => __('Pilot', 'awt-volunteer'),
            'ground_transport' => __('Ground Transport', 'awt-volunteer'),
            'foster' => __('Foster', 'awt-volunteer'),
            'admin' => __('Administrative', 'awt-volunteer'),
            'coordinator' => __('Transport Coordinator', 'awt-volunteer')
        );
        return isset($types[$type]) ? $types[$type] : $type;
    }

    private function get_status_badge($status) {
        $badges = array(
            'active' => '<span class="awt-badge awt-badge-success">Active</span>',
            'inactive' => '<span class="awt-badge awt-badge-warning">Inactive</span>',
            'pending' => '<span class="awt-badge awt-badge-info">Pending</span>'
        );
        return isset($badges[$status]) ? $badges[$status] : $status;
    }

    private function get_mission_count($volunteer_id) {
        global $wpdb;
        // This would need to be implemented based on your missions table structure
        return '0'; // Placeholder
    }

    private function get_certification_status($volunteer_id) {
        global $wpdb;
        $certs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}awt_pilot_certifications WHERE volunteer_id = %d",
                $volunteer_id
            )
        );
        
        if (empty($certs)) {
            return '<span class="awt-badge awt-badge-warning">None</span>';
        }
        
        $valid = true;
        foreach ($certs as $cert) {
            if (strtotime($cert->expiration_date) < time()) {
                $valid = false;
                break;
            }
        }
        
        return $valid ? 
            '<span class="awt-badge awt-badge-success">Valid</span>' : 
            '<span class="awt-badge awt-badge-error">Expired</span>';
    }

    private function get_background_check_status($check_date) {
        if (!$check_date) {
            return '<span class="awt-badge awt-badge-warning">Not Completed</span>';
        }
        
        $check_date = strtotime($check_date);
        $one_year_ago = strtotime('-1 year');
        
        return $check_date > $one_year_ago ? 
            '<span class="awt-badge awt-badge-success">Valid</span>' : 
            '<span class="awt-badge awt-badge-error">Expired</span>';
    }
}
