<?php
/**
 * Plugin settings page
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
            settings_fields('awt_volunteer_settings');
            do_settings_sections('awt_volunteer_settings');
        ?>

        <div class="awt-settings-container">
            <!-- General Settings -->
            <div class="awt-settings-section">
                <h2><?php _e('General Settings', 'awt-volunteer'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="awt_organization_name">
                                <?php _e('Organization Name', 'awt-volunteer'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" id="awt_organization_name" 
                                   name="awt_volunteer_settings[organization_name]" 
                                   value="<?php echo esc_attr(get_option('awt_organization_name', 'Angels Wings Transportation')); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="awt_contact_email">
                                <?php _e('Contact Email', 'awt-volunteer'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="email" id="awt_contact_email" 
                                   name="awt_volunteer_settings[contact_email]" 
                                   value="<?php echo esc_attr(get_option('awt_contact_email')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Email address for notifications and volunteer communications', 'awt-volunteer'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Notification Settings -->
            <div class="awt-settings-section">
                <h2><?php _e('Notification Settings', 'awt-volunteer'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Email Notifications', 'awt-volunteer'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="awt_volunteer_settings[notify_new_application]" 
                                       value="1" <?php checked(get_option('awt_notify_new_application'), 1); ?>>
                                <?php _e('New application received', 'awt-volunteer'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="awt_volunteer_settings[notify_background_complete]" 
                                       value="1" <?php checked(get_option('awt_notify_background_complete'), 1); ?>>
                                <?php _e('Background check completed', 'awt-volunteer'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="awt_volunteer_settings[notify_certification_expiring]" 
                                       value="1" <?php checked(get_option('awt_notify_certification_expiring'), 1); ?>>
                                <?php _e('Certification expiring soon', 'awt-volunteer'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Notification Recipients', 'awt-volunteer'); ?></th>
                        <td>
                            <textarea name="awt_volunteer_settings[additional_recipients]" 
                                      rows="3" class="regular-text"><?php 
                                echo esc_textarea(get_option('awt_additional_recipients')); 
                            ?></textarea>
                            <p class="description">
                                <?php _e('Additional email addresses to receive notifications (one per line)', 'awt-volunteer'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Background Check Settings -->
            <div class="awt-settings-section">
                <h2><?php _e('Background Check Integration', 'awt-volunteer'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Background Check Provider', 'awt-volunteer'); ?>
                        </th>
                        <td>
                            <select name="awt_volunteer_settings[background_check_provider]" id="awt_background_provider">
                                <option value="none" <?php selected(get_option('awt_background_check_provider'), 'none'); ?>>
                                    <?php _e('None', 'awt-volunteer'); ?>
                                </option>
                                <option value="checkr" <?php selected(get_option('awt_background_check_provider'), 'checkr'); ?>>
                                    <?php _e('Checkr', 'awt-volunteer'); ?>
                                </option>
                                <option value="sterling" <?php selected(get_option('awt_background_check_provider'), 'sterling'); ?>>
                                    <?php _e('Sterling', 'awt-volunteer'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr class="provider-settings checkr-settings">
                        <th scope="row"><?php _e('Checkr API Key', 'awt-volunteer'); ?></th>
                        <td>
                            <input type="password" name="awt_volunteer_settings[checkr_api_key]" 
                                   value="<?php echo esc_attr(get_option('awt_checkr_api_key')); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Background Check Requirements', 'awt-volunteer'); ?></th>
                        <td>
                            <?php
                            $required_roles = get_option('awt_background_check_roles', array('pilot', 'ground_transport'));
                            $volunteer_roles = array(
                                'pilot' => __('Pilots', 'awt-volunteer'),
                                'ground_transport' => __('Ground Transport', 'awt-volunteer'),
                                'foster' => __('Fosters', 'awt-volunteer'),
                                'admin' => __('Administrative', 'awt-volunteer'),
                                'coordinator' => __('Transport Coordinators', 'awt-volunteer')
                            );
                            foreach ($volunteer_roles as $role_key => $role_label) {
                                echo '<label>';
                                echo '<input type="checkbox" name="awt_volunteer_settings[background_check_roles][]" ';
                                echo 'value="' . esc_attr($role_key) . '" ';
                                echo in_array($role_key, $required_roles) ? 'checked' : '';
                                echo '> ' . esc_html($role_label);
                                echo '</label><br>';
                            }
                            ?>
                            <p class="description">
                                <?php _e('Select which volunteer roles require background checks', 'awt-volunteer'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Custom Fields -->
            <div class="awt-settings-section">
                <h2><?php _e('Custom Fields', 'awt-volunteer'); ?></h2>
                
                <div id="awt-custom-fields">
                    <?php
                    $custom_fields = get_option('awt_custom_fields', array());
                    if (!empty($custom_fields)) {
                        foreach ($custom_fields as $index => $field) {
                            ?>
                            <div class="custom-field-row">
                                <input type="text" name="awt_volunteer_settings[custom_fields][<?php echo $index; ?>][label]" 
                                       value="<?php echo esc_attr($field['label']); ?>" placeholder="Field Label">
                                
                                <select name="awt_volunteer_settings[custom_fields][<?php echo $index; ?>][type]">
                                    <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                    <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Text Area</option>
                                    <option value="checkbox" <?php selected($field['type'], 'checkbox'); ?>>Checkbox</option>
                                    <option value="select" <?php selected($field['type'], 'select'); ?>>Dropdown</option>
                                </select>
                                
                                <select name="awt_volunteer_settings[custom_fields][<?php echo $index; ?>][volunteer_type]">
                                    <option value="all" <?php selected($field['volunteer_type'], 'all'); ?>>All Types</option>
                                    <option value="pilot" <?php selected($field['volunteer_type'], 'pilot'); ?>>Pilots Only</option>
                                    <option value="ground_transport" <?php selected($field['volunteer_type'], 'ground_transport'); ?>>Ground Transport Only</option>
                                    <option value="foster" <?php selected($field['volunteer_type'], 'foster'); ?>>Fosters Only</option>
                                </select>
                                
                                <button type="button" class="button remove-field"><?php _e('Remove', 'awt-volunteer'); ?></button>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <button type="button" class="button button-secondary" id="add-custom-field">
                    <?php _e('Add Custom Field', 'awt-volunteer'); ?>
                </button>
            </div>

            <!-- Save Button -->
            <p class="submit">
                <?php submit_button(__('Save Settings', 'awt-volunteer'), 'primary', 'submit', false); ?>
            </p>
        </div>
    </form>
</div>

<!-- Template for new custom field row -->
<script type="text/template" id="custom-field-template">
    <div class="custom-field-row">
        <input type="text" name="awt_volunteer_settings[custom_fields][{{index}}][label]" 
               placeholder="<?php _e('Field Label', 'awt-volunteer'); ?>">
        
        <select name="awt_volunteer_settings[custom_fields][{{index}}][type]">
            <option value="text"><?php _e('Text', 'awt-volunteer'); ?></option>
            <option value="textarea"><?php _e('Text Area', 'awt-volunteer'); ?></option>
            <option value="checkbox"><?php _e('Checkbox', 'awt-volunteer'); ?></option>
            <option value="select"><?php _e('Dropdown', 'awt-volunteer'); ?></option>
        </select>
        
        <select name="awt_volunteer_settings[custom_fields][{{index}}][volunteer_type]">
            <option value="all"><?php _e('All Types', 'awt-volunteer'); ?></option>
            <option value="pilot"><?php _e('Pilots Only', 'awt-volunteer'); ?></option>
            <option value="ground_transport"><?php _e('Ground Transport Only', 'awt-volunteer'); ?></option>
            <option value="foster"><?php _e('Fosters Only', 'awt-volunteer'); ?></option>
        </select>
        
        <button type="button" class="button remove-field"><?php _e('Remove', 'awt-volunteer'); ?></button>
    </div>
</script>

<style>
    .awt-settings-section {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .custom-field-row {
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .provider-settings {
        display: none;
    }
    
    .provider-settings.active {
        display: table-row;
    }
</style>

<script>
jQuery(document).ready(function($) {
    // Show/hide provider settings based on selection
    $('#awt_background_provider').on('change', function() {
        $('.provider-settings').removeClass('active');
        $('.' + $(this).val() + '-settings').addClass('active');
    }).trigger('change');
    
    // Add custom field
    var fieldIndex = <?php echo count($custom_fields); ?>;
    $('#add-custom-field').on('click', function() {
        var template = $('#custom-field-template').html();
        template = template.replace(/{{index}}/g, fieldIndex++);
        $('#awt-custom-fields').append(template);
    });
    
    // Remove custom field
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.custom-field-row').remove();
    });
});
</script>
