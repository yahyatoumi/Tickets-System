import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    server: {
        hmr: {
            host: "127.0.0.1",
        },
        port: 8080,
        host: true,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // buildDirectory: '/', // This ensures the manifest is generated in the .vite directory
        }),
        // Add this vue plugin configuration here
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        })
    ],
    // build: {
    //     outDir: 'public/build', // Ensure assets are built in the public directory
    //     manifest: true,         // Laravel needs this to load assets
    // },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});