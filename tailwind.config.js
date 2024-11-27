import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
              indigo: {
                100: '#e6e8ff',
                300: '#b2b7ff',
                400: '#7886d7',
                500: '#6574cd',
                600: '#5661b3',
                800: '#2f365f',
                900: '#191e38',
              },
            },
            fontFamily: {
              sans: ['"Cerebri Sans"', ...defaultTheme.fontFamily.sans],
            },
          },
    },
    plugins: [],
};
