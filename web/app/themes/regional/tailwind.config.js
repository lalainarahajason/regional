module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    purge: {
        layers: ['components', 'utilities'],
        content:['./*.php',
            './src/**/*.php',
            './src/Views/*.twig',
            './src/Views/**/*.twig',]
    },
    theme: {
        fontFamily: {
            'sans' : ['Spartan, sans-serif' ]
        },
        fontSize:{
            'small' : '.6rem',
            'discover' : '3.125rem'
        },
        lineHeight:{
          'leading-hfour' : '1.56'
        },
        colors:{
            transparent: 'transparent',
            current: 'currentColor',
            blue:{
                DEFAULT: '#08316E',
                    light: '#54C5E3',
                    dark: '#314F79',
                    darker: '#2b446a',
                    ocean:'#dbf3f5'
                },
            yellow: {
                DEFAULT:'#F8E800'
            }
        },

        screens: {
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'custom' : '980px',
                        'xl': '1280px',
                        '2xl': '1536px',
                        '3xl': '1600px',
                        'xxl' : '1700px'
        },
    },
    variants: {textColor: ['responsive', 'hover', 'focus', 'visited'],},
   plugins: [
        ({addUtilities}) => {
            const utils = {
                                        '.translate-x-half': {
                                            transform: 'translateX(50%)',
                                        },
                        };
                                    addUtilities(utils, ['responsive'])
                    }
                    ]
                    };