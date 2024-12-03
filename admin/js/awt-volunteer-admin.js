jQuery(document).ready(function($) {
    // Initialize the calendar if it exists
    if ($('#awt-calendar').length) {
        initializeCalendar();
    }

    // Initialize volunteer distribution chart if it exists
    if ($('.awt-stats-chart').length) {
        initializeVolunteerChart();
    }

    // Handle volunteer detail view
    $('.awt-view-volunteer').on('click', function(e) {
        e.preventDefault();
        var volunteerId = $(this).data('id');
        loadVolunteerDetails(volunteerId);
    });

    // Handle modal close
    $('.awt-modal-close').on('click', function() {
        $('#awt-volunteer-details-modal').hide();
    });

    // Close modal when clicking outside
    $(window).on('click', function(e) {
        if ($(e.target).hasClass('awt-modal')) {
            $('#awt-volunteer-details-modal').hide();
        }
    });

    // Handle bulk actions
    $('#doaction, #doaction2').click(function(e) {
        var action = $(this).prev('select').val();
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected volunteers?')) {
                e.preventDefault();
            }
        }
    });

    // Handle broadcast message
    $('#awt-send-broadcast').on('click', function(e) {
        e.preventDefault();
        showBroadcastModal();
    });

    // Handle volunteer status toggle
    $('.awt-toggle-status').on('click', function(e) {
        e.preventDefault();
        var volunteerId = $(this).data('id');
        var currentStatus = $(this).data('status');
        toggleVolunteerStatus(volunteerId, currentStatus);
    });

    // Calendar initialization function
    function initializeCalendar() {
        $('#awt-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: {
                url: ajaxurl,
                data: {
                    action: 'awt_get_calendar_events',
                    nonce: awtVolunteerAdmin.nonce
                }
            },
            eventClick: function(event) {
                showEventDetails(event);
            }
        });
    }

    // Initialize volunteer distribution chart
    function initializeVolunteerChart() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'awt_get_volunteer_distribution',
                nonce: awtVolunteerAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    renderVolunteerChart(response.data);
                }
            }
        });
    }

    // Render volunteer distribution chart
    function renderVolunteerChart(data) {
        const ctx = $('.awt-stats-chart')[0].getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: [
                        '#2271b1',
                        '#00a32a',
                        '#dba617',
                        '#d63638',
                        '#3582c4'
                    ]
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'right'
                }
            }
        });
    }

    // Load volunteer details
    function loadVolunteerDetails(volunteerId) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'awt_get_volunteer_details',
                volunteer_id: volunteerId,
                nonce: awtVolunteerAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#awt-volunteer-details-content').html(response.data.html);
                    $('#awt-volunteer-details-modal').show();
                }
            }
        });
    }

    // Toggle volunteer status
    function toggleVolunteerStatus(volunteerId, currentStatus) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'awt_toggle_volunteer_status',
                volunteer_id: volunteerId,
                current_status: currentStatus,
                nonce: awtVolunteerAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    }

    // Show broadcast message modal
    function showBroadcastModal() {
        const modal = $('<div>').addClass('awt-modal').attr('id', 'awt-broadcast-modal');
        const content = $('<div>').addClass('awt-modal-content');
        
        content.append('<h2>Send Broadcast Message</h2>');
        content.append('<select id="broadcast-recipient-type"><option value="all">All Volunteers</option><option value="pilots">Pilots Only</option><option value="ground">Ground Transport Only</option><option value="fosters">Fosters Only</option><option value="admin">Administrative Only</option></select>');
        content.append('<textarea id="broadcast-message" rows="5" placeholder="Enter your message"></textarea>');
        content.append('<button class="button button-primary" id="send-broadcast">Send Message</button>');
        content.append('<button class="button awt-modal-close">Cancel</button>');

        modal.append(content);
        $('body').append(modal);
        modal.show();

        // Handle send broadcast
        $('#send-broadcast').on('click', function() {
            sendBroadcastMessage();
        });

        // Handle modal close
        modal.find('.awt-modal-close').on('click', function() {
            modal.remove();
        });
    }

    // Send broadcast message
    function sendBroadcastMessage() {
        const recipientType = $('#broadcast-recipient-type').val();
        const message = $('#broadcast-message').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'awt_send_broadcast_message',
                recipient_type: recipientType,
                message: message,
                nonce: awtVolunteerAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#awt-broadcast-modal').remove();
                    alert('Message sent successfully!');
                } else {
                    alert('Error sending message. Please try again.');
                }
            }
        });
    }

    // Handle filters and search
    $('#volunteer-search-input').on('keyup', function(e) {
        if (e.keyCode === 13) {
            $('#awt-volunteer-filter').submit();
        }
    });

    // Initialize tooltips
    $('[data-tooltip]').tooltip();
});
