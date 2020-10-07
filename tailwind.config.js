module.exports = {
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
  purge: [
      './resources/**/*.html',
      './resources/**/*.php',
      './resources/**/*.vue',
      './resources/**/*.js',
  ],
  theme: {
    fontFamily: {
        'body': ['Nunito'],
        'display': ['Nunito'],
        'sans': ['Nunito', 'sans-serif'],
    },
    extend: {},
  },
  variants: {},
  plugins: [],
}
