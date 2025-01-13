const defaultTheme = require('tailwindcss/defaultTheme');
/** @type {import('tailwindcss').Config} */
export default {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
  ],
    darkMode: 'class',
  theme: {
    extend: {
        fontFamily: {
            sans: ['Almarai', ...defaultTheme.fontFamily.sans],
        },
        keyframes: {
            glow: {
                '0%, 100%': { filter: 'drop-shadow(0 0 0px #f52e0b)'},
                '50%': {
                    fill: '#fff',
                    filter: 'drop-shadow(0 0 5px #ffffff) drop-shadow(0 0 10px #F59E0BFF) drop-shadow(0 0 15px #f52e0b) drop-shadow(0 0 40px #f52e0b)'
                },
            }
        },
        animation: {
            glow: 'glow 3s infinite',
        },
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '4rem',
                xl: '10rem',
                '2xl': '14rem',
            },
        },
    },
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}

