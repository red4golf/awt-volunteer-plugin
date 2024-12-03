import React from 'react';

export const Select = ({
  label,
  name,
  value,
  onChange,
  options,
  required,
  className = ''
}) => (
  <div className="form-control w-full">
    <label className="label">
      <span className="label-text">{label}</span>
    </label>
    <select
      name={name}
      value={value}
      onChange={onChange}
      required={required}
      className={`select select-bordered w-full ${className}`}
    >
      <option value="">Select...</option>
      {options.map(option => (
        <option key={option.value} value={option.value}>
          {option.label}
        </option>
      ))}
    </select>
  </div>
);