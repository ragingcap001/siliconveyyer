const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // The legacy Bootstrap stylesheet is still loaded for pages that have not
    // been converted yet, so Tailwind's .container is switched off to stop the
    // two from fighting over the same class name. Layout uses .shell instead.
    corePlugins: { container: false },

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', ...defaultTheme.fontFamily.sans],
                display: ['Sora', 'Inter', 'ui-sans-serif', 'system-ui'],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                // Brand. A task marketplace should read as trustworthy and
                // energetic, so violet/indigo with a lime "earnings" accent.
                brand: {
                    50: '#f2f4ff',
                    100: '#e5e9ff',
                    200: '#ccd4ff',
                    300: '#a8b5ff',
                    400: '#7f8bff',
                    500: '#5b61f8',
                    600: '#4640e0',
                    700: '#3a33b7',
                    800: '#312d92',
                    900: '#2c2a74',
                    950: '#1a1a44',
                },
                // Money / earnings / success
                earn: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                },
                ink: {
                    50: '#f6f7f9',
                    100: '#eceef2',
                    200: '#d5d9e2',
                    300: '#b0b8c9',
                    400: '#8592ab',
                    500: '#657591',
                    600: '#505e79',
                    700: '#424d62',
                    800: '#394253',
                    900: '#333a47',
                    950: '#22262f',
                },
            },

            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.75rem',
            },

            boxShadow: {
                soft: '0 1px 2px 0 rgb(16 24 40 / 0.04), 0 1px 3px 0 rgb(16 24 40 / 0.06)',
                card: '0 4px 6px -2px rgb(16 24 40 / 0.03), 0 12px 16px -4px rgb(16 24 40 / 0.08)',
                lift: '0 8px 8px -4px rgb(16 24 40 / 0.04), 0 20px 24px -4px rgb(16 24 40 / 0.10)',
                glow: '0 0 0 1px rgb(91 97 248 / 0.18), 0 18px 40px -12px rgb(91 97 248 / 0.45)',
                inner_line: 'inset 0 1px 0 0 rgb(255 255 255 / 0.06)',
            },

            backgroundImage: {
                'grid-light':
                    'linear-gradient(to right, rgb(15 23 42 / 0.055) 1px, transparent 1px), linear-gradient(to bottom, rgb(15 23 42 / 0.055) 1px, transparent 1px)',
                'grid-dark':
                    'linear-gradient(to right, rgb(255 255 255 / 0.06) 1px, transparent 1px), linear-gradient(to bottom, rgb(255 255 255 / 0.06) 1px, transparent 1px)',
                'brand-gradient': 'linear-gradient(135deg, #5b61f8 0%, #7f8bff 45%, #14b8a6 100%)',
                'brand-sheen': 'linear-gradient(135deg, rgb(255 255 255 / 0.22) 0%, transparent 55%)',
            },

            backgroundSize: { grid: '48px 48px' },

            // Extra steps so colour/opacity modifiers like bg-brand-500/12 work.
            opacity: {
                2: '0.02', 3: '0.03', 4: '0.04', 6: '0.06', 8: '0.08',
                12: '0.12', 14: '0.14', 15: '0.15', 18: '0.18', 22: '0.22',
                28: '0.28', 35: '0.35', 45: '0.45', 55: '0.55', 65: '0.65', 85: '0.85',
            },

            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'pulse-ring': {
                    '0%': { transform: 'scale(0.9)', opacity: '0.7' },
                    '70%': { transform: 'scale(1.6)', opacity: '0' },
                    '100%': { opacity: '0' },
                },
            },

            animation: {
                'fade-up': 'fade-up .7s cubic-bezier(.16,1,.3,1) both',
                'fade-in': 'fade-in .8s ease both',
                float: 'float 7s ease-in-out infinite',
                shimmer: 'shimmer 2.2s infinite',
                marquee: 'marquee 38s linear infinite',
                'pulse-ring': 'pulse-ring 2.6s cubic-bezier(.24,.6,.35,1) infinite',
            },

            transitionTimingFunction: {
                spring: 'cubic-bezier(.16,1,.3,1)',
            },

            screens: { xs: '460px' },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
