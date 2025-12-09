import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
            rollupOptions: {
                output: {
                    // Customize the name for asset files (e.g., images, fonts, CSS)
                    assetFileNames: 'assets/[name].[ext]',

                    // Customize the name for code-split chunks
                    chunkFileNames: 'assets/[name].js',

                    // Customize the name for entry point files (e.g., your main JS/CSS files)
                    entryFileNames: 'assets/[name].js',
                },
            },
        },
});
