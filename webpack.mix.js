let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.disableNotifications()
    .js('resources/app.js', 'public/js')
    .postCss('resources/app.pcss', 'public/css', [
        tailwindcss('tailwind.config.js')
    ])
    .version();
