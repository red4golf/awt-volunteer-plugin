<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class AWT_Applications_List_Table extends WP_List_Table {
    
    public function __construct() {
        parent::__construct(array(
            'singular' => 'application',
            'plural'   => 'applications',
            'ajax'     => false
        ));
    }

    public function get_columns() {
        return array(
            'cb'           => '<input type="checkbox" />',
            'name'         => __('Name', 'awt-volunteer'),
            'type'         => __('Volunteer Type', 'awt-volunteer'),
            'email'        => __('Email', 'awt-volunteer'),
            'phone'        => __('Phone', 'awt-volunteer'),
            'location'     => __('Location', 'awt-volunteer'),
            'submitted'    => __('Submitted', 'awt-volunteer'),
            'background'   => __('Background Check', 'awt-volunteer'),
            'status'       => __('Status', 'awt-volunteer'),
            'actions'      => __('Actions', 'awt-volunteer')
        );
    }

    public function get_sortable_columns() {
        return array(
            'name'      => array('name', true),
            'type'      => array('type', false),
            'submitted' => array('submitted', false),
            'status'    => array('status', false)
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
        $total_items = $this->get_total_applications();

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $this->items = $this->get_applications($per_page, $current_page);
    }

    private function get_applications($per_page = 20, $page_number = 1) {
        global $wpdb;

        $sql = "SELECT a.*, u.display_name, u.user_email 
                FROM {$wpdb->prefix}awt_volunteers a
                LEFT JOIN {$wpdb->users} u ON a.user_id = u.ID
                WHERE a.status = 'pending'";

        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $sql .= " AND (u.display_name LIKE '%$search%' 
                     OR u.user_email LIKE '%$search%'
                     OR a.phone LIKE '%$search%')";
        }

        if (!empty($_REQUEST['volunteer_type'])) {
            $type = esc_sql($_REQUEST['volunteer_type']);
            $sql .= " AND a.volunteer_type = '$type'";
        }

        if (!empty($_REQUEST['background_check'])) {
            $background = esc_sql($_REQUEST['background_check']);
            if ($background === 'completed') {
                $sql .= " AND a.background_check_date IS NOT NULL";
            } else {
                $sql .= " AND a.background_check_date IS NULL";
            }
        }

        $orderby = (!empty($_REQUEST['orderby'])) ? esc_sql($_REQUEST['orderby']) : 'created_at';
        $order = (!empty($_REQUEST['order'])) ? esc_sql($_REQUEST['order']) : 'DESC';
        
        $sql .= " ORDER BY $orderby $order";
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        return $wpdb->get_results($sql, ARRAY_A);
    }

    private function get_total_applications() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}awt_volunteers WHERE status = 'pending'";
        
        if (!empty($_REQUEST['volunteer_type'])) {
            $type = esc_sql($_REQUEST['volunteer_type']);
            $sql .= " AND volunteer_type = '$type'";
        }
        
        return $wpdb->get_var($sql);
    }

    public function column_name($item) {
        $actions = array(
            'view'    => sprintf(
                '<a href="#" class="awt-view-application" data-id="%s">%s</a>',
                $item['id'],
                __('View Details', 'awt-volunteer')
            ),
            'approve' => sprintf(
                '<a href="%s" class="awt-approve-application" data-id="%s">%s</a>',
                wp_nonce_url(admin_url('admin-post.php?action=approve_application&id=' . $item['id']), 'approve_application_' . $item['id']),
                $item['id'],
                __('Approve', 'awt-volunteer')
            ),
            'reject'  => sprintf(
                '<a href="%s" class="awt-reject-application" data-id="%s">%s</a>',
                wp_nonce_url(admin_url('admin-post.php?action=reject_application&id=' . $item['id']), 'reject_application_' . $item['id']),
                $item['id'],
                __('Reject', 'awt-volunteer')
            )
        );

        return sprintf(
            '%1$s %2$s',
            $item['display_name'],
            $this->row_actions($actions)
        );
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="application[]" value="%s" />',
            $item['id']
        );
    }

    public function column_type($item) {
        return $this->get_volunteer_type_label($item['volunteer_type']);
    }

    public function column_background($item) {
        if (empty($item['background_check_date'])) {
            return '<span class="awt-badge awt-badge-warning">' . 
                   __('Pending', 'awt-volunteer') . '</span>';
        }
        
        $check_date = strtotime($item['background_check_date']);
        $one_year_ago = strtotime('-1 year');
        
        return $check_date > $one_year_ago ? 
            '<span class="awt-badge awt-badge-success">' . __('Valid', 'awt-volunteer') . '</span>' : 
            '<span class="awt-badge awt-badge-error">' . __('Expired', 'awt-volunteer') . '</span>';
    }

    public function get_bulk_actions() {
        return array(
            'approve' => __('Approve', 'awt-volunteer'),
            'reject'  => __('Reject', 'awt-volunteer'),
            'export'  => __('Export', 'awt-volunteer')
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

    public function process_bulk_action() {
        if ('approve' === $this->current_action()) {
            $applications = isset($_POST['application']) ? $_POST['application'] : array();
            if (!empty($applications)) {
                foreach ($applications as $application_id) {
                    $this->approve_application($application_id);
                }
                wp_redirect(add_query_arg('approved', count($applications)));
                exit;
            }
        }

        if ('reject' === $this->current_action()) {
            $applications = isset($_POST['application']) ? $_POST['application'] : array();
            if (!empty($applications)) {
                foreach ($applications as $application_id) {
                    $this->reject_application($application_id);
                }
                wp_redirect(add_query_arg('rejected', count($applications)));
                exit;
            }
        }

        if ('export' === $this->current_action()) {
            $this->export_applications();
        }
    }

    private function approve_application($id) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'awt_volunteers',
            array('status' => 'active'),
            array('id' => $id),
            array('%s'),
            array('%d')
        );
        do_action('awt_application_approved', $id);
    }

    private function reject_application($id) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'awt_volunteers',
            array('status' => 'rejected'),
            array('id' => $id),
            array('%s'),
            array('%d')
        );
        do_action('awt_application_rejected', $id);
    }

    private function export_applications() {
        $applications = $this->get_applications(-1);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=volunteer-applications.csv');
        
        $fp = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($fp, array_keys($this->get_columns()));
        
        // Add data
        foreach ($applications as $application) {
            fputcsv($fp, array(
                $application['display_name'],
                $this->get_volunteer_type_label($application['volunteer_type']),
                $application['user_email'],
                $application['phone'],
                $application['location'],
                $application['created_at'],
                $this->column_background($application),
                $application['status']
            ));
        }
        
        fclose($fp);
        exit;
    }
}
