import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

export default defineConfig({
    // server: {
    //   hmr: {
    //     host: 'kombet.local',
    //   },
    //   https: {
    //       key: 'D:/laragon/etc/ssl/laragon.key',
    //       cert: 'D:/laragon/etc/ssl/laragon.crt',
    //   },
    // },
    resolve: {
      alias: {
        ziggy: 'vendor/tightenco/ziggy/dist/vue.es.js',
      },
    },
    ssr: {
        noExternal: ['@popperjs/core', 'element-plus', 'axios'],
        external : []
    },
    css: {
        preprocessorOptions: {
            scss: {
                // additionalData: "@use '@/styles/element/index.scss' as *; ",
            },
        },
    },
    plugins: [
        AutoImport({
            resolvers: [
                ElementPlusResolver({
                  importStyle: 'scss',
                }),
            ],
        }),
        Components({
          // allow auto load markdown components under `./src/components/`
          extensions: ['vue', 'md'],
          // allow auto import and register components used in markdown
          include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
          resolvers: [
            ElementPlusResolver({
              importStyle: 'scss',
            }),
          ],
        }),
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/styles/app.scss'
            ],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        rollupOptions: {
          output: {
            entryFileNames: `assets/[name].js`,
            chunkFileNames: `assets/[name].js`,
            assetFileNames: 'assets/[name].css',
          }
        }
    }
});
