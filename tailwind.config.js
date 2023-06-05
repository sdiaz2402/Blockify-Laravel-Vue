module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
      ],
  theme: {
    inset: {
        '0': '0px',
        '1': '1px',
        '4': '4px',
        '5': '5px',
        '7': '7px',
        '8': '8px'
      },
    extend: {
        colors:{
            'block-black':"#080808",
            'block-black-accent':"#121417",
            'block-white':"#E9E9E9",
            'block-gray':'#444547'
        }
    },
  },
  variants: {},
  plugins: [],

  screens: {
    'sm': '640px',
    // => @media (min-width: 640px) { ... }

    'md': '768px',
    // => @media (min-width: 768px) { ... }

    'lg': '1024px',
    // => @media (min-width: 1024px) { ... }

    'xl': '1280px',
    // => @media (min-width: 1280px) { ... }
  }
}
