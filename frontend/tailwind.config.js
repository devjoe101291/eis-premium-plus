/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'rgb(var(--color-primary-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-primary-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-primary-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-primary-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-primary-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-primary-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-primary-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-primary-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-primary-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-primary-dark-text) / <alpha-value>)',
          }
        },
        secondary: {
          DEFAULT: 'rgb(var(--color-secondary-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-secondary-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-secondary-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-secondary-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-secondary-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-secondary-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-secondary-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-secondary-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-secondary-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-secondary-dark-text) / <alpha-value>)',
          }
        },
        success: {
          DEFAULT: 'rgb(var(--color-success-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-success-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-success-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-success-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-success-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-success-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-success-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-success-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-success-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-success-dark-text) / <alpha-value>)',
          }
        },
        danger: {
          DEFAULT: 'rgb(var(--color-danger-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-danger-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-danger-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-danger-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-danger-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-danger-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-danger-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-danger-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-danger-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-danger-dark-text) / <alpha-value>)',
          }
        },
        warning: {
          DEFAULT: 'rgb(var(--color-warning-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-warning-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-warning-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-warning-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-warning-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-warning-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-warning-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-warning-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-warning-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-warning-dark-text) / <alpha-value>)',
          }
        },
        info: {
          DEFAULT: 'rgb(var(--color-info-light-DEFAULT) / <alpha-value>)',
          hover: 'rgb(var(--color-info-light-hover) / <alpha-value>)',
          active: 'rgb(var(--color-info-light-active) / <alpha-value>)',
          bg: 'rgb(var(--color-info-light-bg) / <alpha-value>)',
          text: 'rgb(var(--color-info-light-text) / <alpha-value>)',
          dark: {
            DEFAULT: 'rgb(var(--color-info-dark-DEFAULT) / <alpha-value>)',
            hover: 'rgb(var(--color-info-dark-hover) / <alpha-value>)',
            active: 'rgb(var(--color-info-dark-active) / <alpha-value>)',
            bg: 'rgb(var(--color-info-dark-bg) / <alpha-value>)',
            text: 'rgb(var(--color-info-dark-text) / <alpha-value>)',
          }
        }
      },
      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
      },
      boxShadow: {
        'elegant': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
        'elegant-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04)',
        'elegant-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'soft': '0 2px 8px rgba(0, 0, 0, 0.04)',
        'soft-lg': '0 4px 16px rgba(0, 0, 0, 0.06)',
      },
      backdropBlur: {
        xs: '2px',
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, var(--color-primary-light-DEFAULT) 0%, var(--color-primary-light-hover) 100%)',
        'gradient-primary-dark': 'linear-gradient(135deg, var(--color-primary-dark-DEFAULT) 0%, var(--color-primary-dark-hover) 100%)',
        'gradient-card': 'linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%)',
        'gradient-card-dark': 'linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%)',
      },
    },
  },
  plugins: [],
} 