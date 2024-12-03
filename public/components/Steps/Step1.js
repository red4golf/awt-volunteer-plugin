import React from 'react';

const Step1 = ({ formData, onChange }) => {
  const volunteerTypes = [
    { id: 'pilot', label: 'Pilot', icon: '✈️', 
      description: 'Transport animals by air to their new homes or rescue organizations' },
    { id: 'ground_transport', label: 'Ground Transport', icon: '🚗',
      description: 'Drive animals to their destinations or connecting flights' },
    { id: 'foster', label: 'Foster', icon: '🏠',
      description: 'Provide temporary homes for animals in transit' },
    { id: 'admin', label: 'Administrative', icon: '📝',
      description: 'Help with coordination, paperwork, and logistics' },
    { id: 'coordinator', label: 'Transport Coordinator', icon: '📱',
      description: 'Organize and coordinate transport missions' }
  ];

  return (
    <div className="step-content">
      <h3 className="text-xl font-bold text-gray-900 mb-4">Select Volunteer Type</h3>
      <p className="text-gray-600 mb-6">Choose how you'd like to help animals in need</p>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {volunteerTypes.map(type => (
          <button
            key={type.id}
            onClick={() => onChange({
              target: { name: 'volunteerType', value: type.id }
            })}
            className={`p-6 border-2 rounded-lg transition-colors text-left
                      ${formData.volunteerType === type.id 
                        ? 'border-[#F96D00] bg-[#fff8f3]' 
                        : 'border-gray-300 hover:border-[#F96D00] hover:bg-[#fff8f3]'}`}
          >
            <div className="flex items-start space-x-3">
              <span className="text-3xl">{type.icon}</span>
              <div>
                <span className="block font-medium text-gray-900">{type.label}</span>
                <span className="text-sm text-gray-600 mt-1 block">{type.description}</span>
              </div>
            </div>
          </button>
        ))}
      </div>
    </div>
  );
};

export default Step1;