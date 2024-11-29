import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    server: {
        hmr: {
            host: "127.0.0.1",
        },
        port: 3000,
        host: true,
    },
    base: '/',
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
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
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});