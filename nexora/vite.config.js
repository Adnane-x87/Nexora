import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/css/shop.css',
                'resources/css/login.css',
                'resources/css/dashboard.css',
                'resources/css/checkout.css',
                'resources/js/app.js',
                'resources/js/main.js',
                'resources/js/shop.js',
                'resources/js/login.js',
                'resources/js/register.js',
                'resources/js/dashboard.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
