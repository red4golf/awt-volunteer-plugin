import React from 'react';

export const Checkbox = ({
  label,
  name,
  checked,
  onChange,
  className = ''
}) => (
  <label className="label cursor-pointer">
    <span className="label-text">{label}</span>
    <input
      type="checkbox"
      name={name}
      checked={checked}
      onChange={onChange}
      className={`checkbox ${className}`}
    />
  </label>
);