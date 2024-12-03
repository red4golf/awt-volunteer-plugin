import React, { useState } from 'react';

const VolunteerRegistrationForm = () => {
  const [step, setStep] = useState(1);
  
  const styles = {
    button: "px-4 py-2 bg-[#F96D00] text-white rounded hover:bg-[#cc5a00] transition-colors",
    buttonOutline: "px-4 py-2 border-2 border-[#F96D00] text-[#F96D00] rounded hover:bg-[#F96D00] hover:text-white transition-colors",
    input: "mt-1 block w-full border-2 border-gray-300 rounded p-2 focus:border-[#F96D00] focus:ring-[#F96D00]",
    label: "block text-sm font-medium text-gray-900",
    heading: "text-xl font-bold text-gray-900 mb-4",
    progress: "h-2 bg-[#F96D00]",
    progressContainer: "h-2 bg-gray-200",
    progressText: "text-sm text-gray-900"
  };

  const [formData, setFormData] = useState({
    volunteerType: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    availableDays: [],
    availableTimes: [],
    maxDistance: '',
    pilotLicense: '',
    aircraftType: '',
    totalHours: '',
    vehicleType: '',
    vehicleMake: '',
    vehicleModel: '',
    vehicleYear: '',
    homeType: '',
    hasYard: false,
    hasOtherPets: false,
    emergencyName: '',
    emergencyPhone: '',
    emergencyRelation: '',
    backgroundCheck: false,
    experience: '',
    specialSkills: ''
  });

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'awt_submit_volunteer_application',
          nonce: awtVolunteerData.nonce,
          formData: JSON.stringify(formData)
        })
      });
      
      if (response.ok) {
        setStep(5);
      } else {
        throw new Error('Submission failed');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div className="bg-white p-4">
      <div className="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div className="mb-8">
          <div className="flex justify-between mb-2">
            {['Select Role', 'Personal Info', 'Role Details', 'Final Details'].map((label, index) => (
              <div
                key={label}
                className={`${styles.progressText} ${step > index ? 'text-[#F96D00]' : ''}`}
              >
                {label}
              </div>
            ))}
          </div>
          <div className={styles.progressContainer}>
            <div
              className={styles.progress}
              style={{ width: `${((step - 1) / 4) * 100}%` }}
            />
          </div>
        </div>

        <form onSubmit={handleSubmit}>
          {/* Form steps will be implemented in separate components */}
          <div className="mt-6 flex justify-between">
            {step > 1 && (
              <button
                type="button"
                onClick={() => setStep(prev => prev - 1)}
                className={styles.buttonOutline}
              >
                Back
              </button>
            )}
            {step < 4 ? (
              <button
                type="button"
                onClick={() => setStep(prev => prev + 1)}
                className={styles.button}
              >
                Continue
              </button>
            ) : (
              <button
                type="submit"
                className={styles.button}
              >
                Submit Application
              </button>
            )}
          </div>
        </form>
      </div>
    </div>
  );
};

export default VolunteerRegistrationForm;