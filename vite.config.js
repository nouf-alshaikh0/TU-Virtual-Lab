import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/general.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost:8000',
        },
      }
});
