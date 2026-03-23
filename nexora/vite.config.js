import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/style.css',
                'resources/css/login.css',
                'resources/css/dashboard.css',
                'resources/css/shop.css',
                'resources/js/main.js',
                'resources/js/login.js',
                'resources/js/dashboard.js',
                'resources/js/register.js',
                'resources/js/shop.js',
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
