module.exports = {
  important: true,
  purge: [],
  theme: {
    container: {
      center: true
    },
    extend: {
      fontFamily: {
        poppins: ['Poppins'],
      },
    },
  },
  variants: {},
  plugins: [
    // require("@tailwindcss/custom-forms"),

    function ({
      addComponents
    }) {
      addComponents({
        '.container': {
          maxWidth: '90%',
          '@screen sm': {
            maxWidth: '540px',
          },
          '@screen md': {
            maxWidth: '720px',
          },
          '@screen lg': {
            maxWidth: '960px',
          },
          '@screen xl': {
            maxWidth: '1140px',
          },
        }
      })
    },
  ],
}