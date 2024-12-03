# AWT Volunteer Management System

A WordPress plugin for managing volunteers at Angels Wings Transportation (AWT).

## Features

- Volunteer registration system
- Different registration flows for various volunteer types:
  - Pilots
  - Ground Transport
  - Fosters
  - Administrative
  - Transport Coordinators
- Certification tracking for pilots
- Availability management
- Background check integration
- Automated notifications
- Admin dashboard for volunteer management

## Installation

1. Upload the `awt-volunteer-plugin` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode `[awt_volunteer_form]` to display the volunteer registration form on any page

## Usage

### Adding the Registration Form
Add the following shortcode to any page or post where you want the volunteer registration form to appear:
```
[awt_volunteer_form]
```

### Admin Dashboard
Access the volunteer management dashboard from the WordPress admin menu under "AWT Volunteers"

## Database Structure

### Main Tables
1. `{prefix}_awt_volunteers`
   - Core volunteer information
   - Contact details
   - Background check status
   - Training status
   - Skills and availability

2. `{prefix}_awt_pilot_certifications`
   - Certificate details
   - Verification status
   - Document tracking
   - Expiration management

3. `{prefix}_awt_availability`
   - Schedule management
   - Recurring patterns
   - Timezone support
   - Blackout dates

4. `{prefix}_awt_training_records`
   - Training progress
   - Certification tracking
   - Verification status

5. `{prefix}_awt_service_history`
   - Mission tracking
   - Hours served
   - Service types

## User Roles

- AWT Volunteer: Basic access for volunteers
- AWT Coordinator: Mission and volunteer management
- Administrator: Full system access

## Todo

- [ ] Add document management system
- [ ] Implement training module
- [ ] Create reporting system
- [ ] Add mobile integration
- [ ] Implement volunteer matching system
- [ ] Add weather API integration for pilots
- [ ] Create volunteer recognition system

## Changelog

### 2.0.0
- Enhanced database schema
- Added service history tracking
- Improved certification management
- Added coordinator role
- Enhanced availability scheduling
- Added training records

### 1.0.0
- Initial release
- Basic volunteer registration system
- Admin dashboard
- Database structure
- Notification system