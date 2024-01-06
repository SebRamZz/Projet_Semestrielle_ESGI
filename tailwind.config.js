/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      spacing: {
        '466': '46rem',
        '32': '8rem'
      },

    },

  },
  plugins: [],
}