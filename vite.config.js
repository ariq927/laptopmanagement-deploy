import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
        'resources/assets/vendor/fonts/boxicons.scss',
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/vendor/scss/main.scss',
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/vendor/scss/theme-default.scss',
        'resources/assets/vendor/scss/_theme/_theme.scss',
        'resources/assets/vendor/scss/custom-override.scss',
        'resources/assets/css/demo.css',
        'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
        'resources/assets/vendor/js/helpers.js',
        'resources/assets/js/config.js',
        'resources/assets/vendor/libs/popper/popper.js',
        'resources/assets/vendor/js/bootstrap.js',
        'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
        'resources/assets/vendor/js/menu.js',
        'resources/assets/js/main.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
    },
    server: {
        https: false, // Development bisa HTTP
        host: true,
    },
});