import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/vendor/scss/core.scss',
                'resources/assets/vendor/scss/theme-default.scss',
                'resources/assets/vendor/scss/_theme/_theme.scss',
                'resources/assets/vendor/scss/custom-override.scss',
                'resources/assets/css/demo.css',
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