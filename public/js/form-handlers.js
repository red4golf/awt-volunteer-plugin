const handlePilotSubmission = async (formData) => {
    try {
        const response = await fetch(awt_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'submit_pilot_registration',
                nonce: awt_ajax.pilot_nonce,
                data: JSON.stringify(formData)
            })
        });

        const result = await response.json();
        
        if (result.success) {
            return {
                success: true,
                volunteerId: result.data.volunteer_id
            };
        } else {
            throw new Error(result.data);
        }
    } catch (error) {
        console.error('Pilot registration error:', error);
        throw error;
    }
};

const handleGroundTransportSubmission = async (formData) => {
    try {
        const response = await fetch(awt_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'submit_ground_registration',
                nonce: awt_ajax.ground_nonce,
                data: JSON.stringify(formData)
            })
        });

        const result = await response.json();
        
        if (result.success) {
            return {
                success: true,
                volunteerId: result.data.volunteer_id
            };
        } else {
            throw new Error(result.data);
        }
    } catch (error) {
        console.error('Ground transport registration error:', error);
        throw error;
    }
};

export { handlePilotSubmission, handleGroundTransportSubmission };