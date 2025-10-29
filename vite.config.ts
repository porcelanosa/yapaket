import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
// import path from 'path';
import { defineConfig } from 'vite';
// import { createSvgIconsPlugin } from 'vite-plugin-svg-icons';
import VitePluginSvgSpritemap from 'vite-plugin-svg-spritemap';

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
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/post-gallery.js'],
            // ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        // vue({
        //     template: {
        //         transformAssetUrls: {
        //             base: null,
        //             includeAbsolute: false,
        //         },
        //     },
        // }),
        // svgSprite({
        //     // путь к папке с SVG
        //     include: ['resources/svg/**/*.svg'],
        //     // формат id иконок
        //     symbolId: 'icon-[name]',
        //     exportType: 'vanilla',
        //     injectTo: 'body-last',
        //     // оптимизация
        //     svgo: {
        //         plugins: [
        //             { name: 'removeAttrs', params: { attrs: '(fill|stroke|style)' } },
        //             { name: 'removeDimensions', active: true },
        //         ],
        //     },
        // }),
        // Генерирует статический sprite.svg
        VitePluginSvgSpritemap({
            pattern: 'resources/svg/**/*.svg',
            filename: 'sprite.svg',
            prefix: 'icon',
            svgo: {plugins: [
                    {
                        name: 'preset-default',
                        params: {
                            overrides: {
                                removeViewBox: false, // 👈 Не удалять viewBox!
                            },
                        },
                    },
                ],},
            emit:true
        }),
        // createSvgIconsPlugin({
        //     // Путь к SVG-иконкам
        //     iconDirs: [path.resolve(process.cwd(), 'resources/svg')],
        //     // Формат ID символа
        //     symbolId: 'icon-[name]',
        //     // Оптимизация
        //     svgoOptions: true,
        // }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
