import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';  // Импортируйте плагин

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,  // Это опция для автоматической перезагрузки
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5175,
        strictPort: true,
        watch: {
            usePolling: true,
        },
        hmr: {
            host: 'localhost',
        },
    },
});
