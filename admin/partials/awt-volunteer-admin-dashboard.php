<?php
/**
 * Admin dashboard template
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="awt-admin-dashboard">
        <!-- Summary Cards -->
        <div class="awt-dashboard-cards">
            <div class="awt-card">
                <h3><?php _e('Total Volunteers', 'awt-volunteer'); ?></h3>
                <div class="awt-card-number"><?php echo esc_html($this->get_total_volunteers()); ?></div>
                <div class="awt-card-meta">
                    <span class="active"><?php echo esc_html($this->get_active_volunteers()); ?> active</span>
                </div>
            </div>

            <div class="awt-card">
                <h3><?php _e('Pending Applications', 'awt-volunteer'); ?></h3>
                <div class="awt-card-number"><?php echo esc_html($this->get_pending_applications()); ?></div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=awt-volunteer-applications')); ?>" 
                   class="button button-secondary">
                    <?php _e('Review Applications', 'awt-volunteer'); ?>
                </a>
            </div>

            <div class="awt-card">
                <h3><?php _e('This Month\'s Missions', 'awt-volunteer'); ?></h3>
                <div class="awt-card-number"><?php echo esc_html($this->get_monthly_missions()); ?></div>
                <div class="awt-card-meta">
                    <span class="completed"><?php echo esc_html($this->get_completed_missions()); ?> completed</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="awt-dashboard-recent">
            <h2><?php _e('Recent Activity', 'awt-volunteer'); ?></h2>
            <div class="awt-activity-list">
                <?php $this->display_recent_activity(); ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="awt-dashboard-actions">
            <h2><?php _e('Quick Actions', 'awt-volunteer'); ?></h2>
            <div class="awt-action-buttons">
                <a href="<?php echo esc_url(admin_url('admin.php?page=awt-volunteer-applications&action=new')); ?>" 
                   class="button button-primary">
                    <?php _e('Add New Volunteer', 'awt-volunteer'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=awt-volunteer-list&action=export')); ?>" 
                   class="button button-secondary">
                    <?php _e('Export Volunteer Data', 'awt-volunteer'); ?>
                </a>
                <a href="#" class="button button-secondary" id="awt-send-broadcast">
                    <?php _e('Send Broadcast Message', 'awt-volunteer'); ?>
                </a>
            </div>
        </div>

        <!-- Upcoming Events Calendar -->
        <div class="awt-dashboard-calendar">
            <h2><?php _e('Upcoming Events & Availability', 'awt-volunteer'); ?></h2>
            <div id="awt-calendar"></div>
        </div>

        <!-- Volunteer Type Distribution -->
        <div class="awt-dashboard-stats">
            <h2><?php _e('Volunteer Distribution', 'awt-volunteer'); ?></h2>
            <div class="awt-stats-chart">
                <?php $this->display_volunteer_distribution(); ?>
            </div>
        </div>
    </div>
</div>
