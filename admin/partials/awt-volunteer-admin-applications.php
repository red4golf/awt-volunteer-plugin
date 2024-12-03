<?php
/**
 * Admin applications review template
 */
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php if (isset($_GET['action']) && $_GET['action'] === 'new'): ?>
        <!-- New Volunteer Application Form -->
        <div class="awt-application-form">
            <h2><?php _e('New Volunteer Application', 'awt-volunteer'); ?></h2>
            <form method="post" action="" enctype="multipart/form-data" id="awt-volunteer-form">
                <?php wp_nonce_field('awt_new_volunteer', 'awt_volunteer_nonce'); ?>
                
                <!-- Basic Information Section -->
                <div class="awt-form-section">
                    <h3><?php _e('Basic Information', 'awt-volunteer'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="volunteer_type"><?php _e('Volunteer Type', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <select name="volunteer_type" id="volunteer_type" required>
                                    <option value=""><?php _e('Select Type', 'awt-volunteer'); ?></option>
                                    <option value="pilot"><?php _e('Pilot', 'awt-volunteer'); ?></option>
                                    <option value="ground_transport"><?php _e('Ground Transport', 'awt-volunteer'); ?></option>
                                    <option value="foster"><?php _e('Foster', 'awt-volunteer'); ?></option>
                                    <option value="admin"><?php _e('Administrative', 'awt-volunteer'); ?></option>
                                    <option value="coordinator"><?php _e('Transport Coordinator', 'awt-volunteer'); ?></option>
                                </select>
                            </td>
                        </tr>
                        
                        <!-- Personal Information -->
                        <?php foreach(['first_name', 'last_name', 'email', 'phone'] as $field): ?>
                            <tr>
                                <th scope="row">
                                    <label for="<?php echo $field; ?>">
                                        <?php echo ucwords(str_replace('_', ' ', $field)); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="<?php echo $field === 'email' ? 'email' : 'text'; ?>" 
                                           name="<?php echo $field; ?>" 
                                           id="<?php echo $field; ?>" 
                                           class="regular-text" 
                                           required>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <th scope="row">
                                <label for="address"><?php _e('Address', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <textarea name="address" id="address" rows="3" class="regular-text"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Volunteer Type Specific Fields -->
                <div id="type-specific-fields" style="display: none;">
                    <!-- Dynamic content loaded via JavaScript -->
                </div>

                <!-- Availability Section -->
                <div class="awt-form-section">
                    <h3><?php _e('Availability', 'awt-volunteer'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Preferred Days', 'awt-volunteer'); ?></th>
                            <td>
                                <?php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                foreach ($days as $day) {
                                    echo '<label class="checkbox-label">';
                                    echo '<input type="checkbox" name="available_days[]" value="' . strtolower($day) . '"> ';
                                    echo $day;
                                    echo '</label><br>';
                                }
                                ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Preferred Times', 'awt-volunteer'); ?></th>
                            <td>
                                <select name="preferred_times[]" multiple="multiple" class="awt-select2">
                                    <option value="morning"><?php _e('Morning (6AM-12PM)', 'awt-volunteer'); ?></option>
                                    <option value="afternoon"><?php _e('Afternoon (12PM-5PM)', 'awt-volunteer'); ?></option>
                                    <option value="evening"><?php _e('Evening (5PM-10PM)', 'awt-volunteer'); ?></option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Travel Distance', 'awt-volunteer'); ?></th>
                            <td>
                                <select name="travel_distance" required>
                                    <option value=""><?php _e('Select Maximum Distance', 'awt-volunteer'); ?></option>
                                    <option value="25"><?php _e('Up to 25 miles', 'awt-volunteer'); ?></option>
                                    <option value="50"><?php _e('Up to 50 miles', 'awt-volunteer'); ?></option>
                                    <option value="100"><?php _e('Up to 100 miles', 'awt-volunteer'); ?></option>
                                    <option value="unlimited"><?php _e('Unlimited', 'awt-volunteer'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Experience & Skills -->
                <div class="awt-form-section">
                    <h3><?php _e('Experience & Skills', 'awt-volunteer'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="experience"><?php _e('Previous Experience', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <textarea name="experience" id="experience" rows="4" class="regular-text"></textarea>
                                <p class="description">
                                    <?php _e('Please describe any relevant volunteer or professional experience.', 'awt-volunteer'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="special_skills"><?php _e('Special Skills', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <textarea name="special_skills" id="special_skills" rows="4" class="regular-text"></textarea>
                                <p class="description">
                                    <?php _e('List any special skills that might be helpful (e.g., medical training, mechanical knowledge, etc.).', 'awt-volunteer'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Emergency Contact -->
                <div class="awt-form-section">
                    <h3><?php _e('Emergency Contact', 'awt-volunteer'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="emergency_name"><?php _e('Contact Name', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="emergency_name" id="emergency_name" class="regular-text" required>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="emergency_phone"><?php _e('Contact Phone', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <input type="tel" name="emergency_phone" id="emergency_phone" class="regular-text" required>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="emergency_relationship"><?php _e('Relationship', 'awt-volunteer'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="emergency_relationship" id="emergency_relationship" class="regular-text" required>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Background Check -->
                <div class="awt-form-section">
                    <h3><?php _e('Background Check Authorization', 'awt-volunteer'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <label for="background_check">
                                    <input type="checkbox" name="background_check" id="background_check" required>
                                    <?php _e('I authorize Angels Wings Transportation to perform a background check and understand that my volunteer status is contingent upon passing this check.', 'awt-volunteer'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Submit Button -->
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" 
                           value="<?php _e('Submit Application', 'awt-volunteer'); ?>">
                </p>
            </form>
        </div>

    <?php else: ?>
        <!-- Applications List View -->
        <div class="awt-applications-list">
            <?php
            // Initialize the applications list table
            $applications_table = new AWT_Applications_List_Table();
            $applications_table->prepare_items();
            $applications_table->display();
            ?>
        </div>
    <?php endif; ?>
</div>

<script type="text/template" id="pilot-fields-template">
    <div class="awt-form-section">
        <h3><?php _e('Pilot Information', 'awt-volunteer'); ?></h3>
        <table class="form-table">
            <!-- Pilot-specific fields here -->
        </table>
    </div>
</script>

<!-- Additional volunteer type templates would go here -->
