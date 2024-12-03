import React from 'react';

export const Button = ({
  children,
  type = 'button',
  variant = 'default',
  onClick,
  className = ''
}) => {
  const variants = {
    default: 'btn-ghost',
    primary: 'btn-primary',
    danger: 'btn-error'
  };

  return (
    <button
      type={type}
      onClick={onClick}
      className={`btn ${variants[variant]} ${className}`}
    >
      {children}
    </button>
  );
};