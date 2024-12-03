import React, { useState } from 'react';
import PilotRegistrationForm from './Forms/PilotRegistrationForm';
import GroundTransportForm from './Forms/GroundTransportForm';

const App = () => {
    const [volunteerType, setVolunteerType] = useState(null);

    const renderForm = () => {
        switch(volunteerType) {
            case 'pilot':
                return <PilotRegistrationForm />;
            case 'ground':
                return <GroundTransportForm />;
            default:
                return (
                    <div className="space-y-4">
                        <h2 className="text-xl font-bold">Select Volunteer Type</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button 
                                className="p-4 border rounded hover:bg-gray-50"
                                onClick={() => setVolunteerType('pilot')}
                            >
                                Pilot
                            </button>
                            <button 
                                className="p-4 border rounded hover:bg-gray-50"
                                onClick={() => setVolunteerType('ground')}
                            >
                                Ground Transport
                            </button>
                        </div>
                    </div>
                );
        }
    };

    return (
        <div className="max-w-4xl mx-auto p-4">
            {renderForm()}
        </div>
    );
};

export default App;