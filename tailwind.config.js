import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "ts-teal": "#00B0D2", // primary brand teal
                "ts-teal-dark": "#007fa0", // hover/active — ~20% darkened teal
                "ts-blue": "#2ea3f2", // secondary blue accent
                "ts-cream": "#F1F0EE",
                "ts-text": "#3f3f3f",
            },
            boxShadow: {
                "ts-card": "0px 2px 18px rgba(0,0,0,0.08)",
            },
        },
    },

    plugins: [forms],
};
