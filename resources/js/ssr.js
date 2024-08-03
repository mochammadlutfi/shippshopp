import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy';
import { Ziggy } from './ziggy';

// import ElementPlus from 'element-plus';
import { ID_INJECTION_KEY } from 'element-plus'

import BaseLayout from './Frontend/Layouts/BaseLayout.vue';

const appName = 'Kang Ebod';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Frontend/${name}.vue`, import.meta.glob('./Frontend/**/*.vue')),
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                // .use(ElementPlus)
                .provide(ID_INJECTION_KEY, {
                    prefix: 1024,
                    current: 0,
                })
                .component('BaseLayout', BaseLayout)
                .use(ZiggyVue, Ziggy);
            
        },
    })
);
