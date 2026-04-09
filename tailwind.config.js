/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './public/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#F97316',
                    hover:   '#EA580C',
                },
                background:       '#F5F5F4',
                card:             '#FFFFFF',
                'text-main':      '#1C1917',
                'text-secondary': '#78716C',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'lg':  '0 4px 24px rgba(0,0,0,0.08)',
                'xl':  '0 8px 40px rgba(0,0,0,0.12)',
                '2xl': '0 16px 64px rgba(0,0,0,0.16)',
            },
        },
    },
    plugins: [],
};
