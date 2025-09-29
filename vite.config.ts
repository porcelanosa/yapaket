import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';

export default defineConfig({
    //
    base: '/',
    server: {
        host: '0.0.0.0',
        port: 5716,
        strictPort: true,
        // Разрешить доступ с этих доменов
        hmr: {
            host: 'yapaket.local',
        },
        allowedHosts: ['yapaket.local', 'localhost', '127.0.0.1', '0.0.0.0'],
        cors: {
            origin: '*',
            credentials: true,
        },
        // https: {
        //     key: '/data/yapaket.local/yapaket.local.key.pem',
        //     cert: '/data/yapaket.local/yapaket.local.pem',
        // }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
