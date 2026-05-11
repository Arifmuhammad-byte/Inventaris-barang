/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        // LIVEWIRE
        "./app/Livewire/**/*.php",
        "./resources/views/livewire/**/*.blade.php",
    ],

    theme: {
        extend: {},
    },

    plugins: [],
}