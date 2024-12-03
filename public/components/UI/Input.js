import React from 'react';

export const Input = ({
  label,
  type = 'text',
  name,
  value,
  onChange,
  required,
  placeholder,
  className = ''
}) => {
  if (type === 'textarea') {
    return (
      <div className="form-control w-full">
        <label className="label">
          <span className="label-text">{label}</span>
        </label>
        <textarea
          name={name}
          value={value}
          onChange={onChange}
          required={required}
          placeholder={placeholder}
          className={`textarea textarea-bordered h-24 ${className}`}
        />
      </div>
    );
  }

  return (
    <div className="form-control w-full">
      <label className="label">
        <span className="label-text">{label}</span>
      </label>
      <input
        type={type}
        name={name}
        value={value}
        onChange={onChange}
        required={required}
        placeholder={placeholder}
        className={`input input-bordered w-full ${className}`}
      />
    </div>
  );
};