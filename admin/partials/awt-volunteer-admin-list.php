<?php
/**
 * Volunteer list template
 */

// Create an instance of our list table
require_once plugin_dir_path(dirname(__FILE__)) . 'class-awt-volunteer-list-table.php';
$volunteer_list_table = new AWT_Volunteer_List_Table();
$volunteer_list_table->prepare_items();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Volunteers', 'awt-volunteer'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=awt-volunteer-applications&action=new')); ?>" 
       class="page-title-action">
        <?php _e('Add New', 'awt-volunteer'); ?>
    </a>

    <form id="awt-volunteer-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        
        <!-- Search Box -->
        <p class="search-box">
            <label class="screen-reader-text" for="volunteer-search-input">
                <?php _e('Search Volunteers:', 'awt-volunteer'); ?>
            </label>
            <input type="search" id="volunteer-search-input" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button(__('Search Volunteers', 'awt-volunteer'), '', '', false, array('id' => 'search-submit')); ?>
        </p>

        <!-- Filters -->
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name="volunteer_type">
                    <option value=""><?php _e('All Types', 'awt-volunteer'); ?></option>
                    <option value="pilot" <?php selected(isset($_GET['volunteer_type']) && $_GET['volunteer_type'] === 'pilot'); ?>>
                        <?php _e('Pilots', 'awt-volunteer'); ?>
                    </option>
                    <option value="ground_transport" <?php selected(isset($_GET['volunteer_type']) && $_GET['volunteer_type'] === 'ground_transport'); ?>>
                        <?php _e('Ground Transport', 'awt-volunteer'); ?>
                    </option>
                    <option value="foster" <?php selected(isset($_GET['volunteer_type']) && $_GET['volunteer_type'] === 'foster'); ?>>
                        <?php _e('Foster', 'awt-volunteer'); ?>
                    </option>
                    <option value="admin" <?php selected(isset($_GET['volunteer_type']) && $_GET['volunteer_type'] === 'admin'); ?>>
                        <?php _e('Administrative', 'awt-volunteer'); ?>
                    </option>
                </select>

                <select name="status">
                    <option value=""><?php _e('All Statuses', 'awt-volunteer'); ?></option>
                    <option value="active" <?php selected(isset($_GET['status']) && $_GET['status'] === 'active'); ?>>
                        <?php _e('Active', 'awt-volunteer'); ?>
                    </option>
                    <option value="inactive" <?php selected(isset($_GET['status']) && $_GET['status'] === 'inactive'); ?>>
                        <?php _e('Inactive', 'awt-volunteer'); ?>
                    </option>
                    <option value="pending" <?php selected(isset($_GET['status']) && $_GET['status'] === 'pending'); ?>>
                        <?php _e('Pending', 'awt-volunteer'); ?>
                    </option>
                </select>

                <?php submit_button(__('Filter', 'awt-volunteer'), '', 'filter_action', false); ?>
            </div>

            <!-- Bulk Actions -->
            <div class="alignleft actions bulkactions">
                <select name="action">
                    <option value="-1"><?php _e('Bulk Actions', 'awt-volunteer'); ?></option>
                    <option value="activate"><?php _e('Activate', 'awt-volunteer'); ?></option>
                    <option value="deactivate"><?php _e('Deactivate', 'awt-volunteer'); ?></option>
                    <option value="delete"><?php _e('Delete', 'awt-volunteer'); ?></option>
                    <option value="export"><?php _e('Export', 'awt-volunteer'); ?></option>
                </select>
                <?php submit_button(__('Apply', 'awt-volunteer'), 'action', '', false); ?>
            </div>
        </div>

        <!-- Volunteer List Table -->
        <?php $volunteer_list_table->display(); ?>
    </form>
</div>

<!-- Volunteer Details Modal -->
<div id="awt-volunteer-details-modal" class="awt-modal" style="display: none;">
    <div class="awt-modal-content">
        <span class="awt-modal-close">&times;</span>
        <div id="awt-volunteer-details-content"></div>
    </div>
</div>
