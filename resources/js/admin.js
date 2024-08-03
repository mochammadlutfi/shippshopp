import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import BaseLayout from './Layouts/AuthenticatedLayout.vue';
import 'moment/locale/id';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Shipp Shopp Vape';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Admin/${name}.vue`, import.meta.glob('./Admin/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
        .use(plugin)
        // .use(DataTablesCore)
        .mixin({
            methods : {
                currency(value){
                    if (typeof value !== "number") {
                        value = parseFloat(value);
                    }
                    var formatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    });
                    return formatter.format(value);
                },
            },
        })
        .use(ZiggyVue, Ziggy)
        .component('BaseLayout', BaseLayout);

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});