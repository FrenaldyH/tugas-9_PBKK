import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Design tokens from notes/DESIGN.md mapped onto Tailwind's theme
            colors: {
                ink: '#171717',
                body: '#4d4d4d',
                mute: '#666666',
                hairline: '#ebebeb',
                canvas: { DEFAULT: '#ffffff', soft: '#fafafa', soft2: '#f5f5f5' },
                // Status badge pairs (soft background + deep text)
                warning: { soft: '#ffefcf', deep: '#ab570a' },
                link: { soft: '#d3e5ff', deep: '#0761d1' },
                error: { soft: '#f7d4d6', deep: '#c50000' },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};
