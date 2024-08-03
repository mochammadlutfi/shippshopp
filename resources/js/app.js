import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from 'ziggy';
// import { Ziggy } from './ziggy';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueGoogleMaps from 'vue-google-maps-community-fork';
import { createPinia } from 'pinia';
const pinia = createPinia()

import BaseLayout from './Frontend/Layouts/BaseLayout.vue';
import UserLayout from './Frontend/Layouts/UserLayout.vue';
import 'moment/locale/id';
import axios from 'axios';
window.document.querySelector('html').classList.add('dark');
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Shipp Shopp Vape';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Frontend/${name}.vue`, import.meta.glob('./Frontend/**/*.vue')),
    setup({ el, App, props, plugin }) {
        el.removeAttribute("data-page");
        const app = createApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, Ziggy)
        .use(pinia)
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
        .component('BaseLayout', BaseLayout)
        .component('UserLayout', UserLayout);
        // .use(VueGoogleMaps, {
        //     load: {
        //         key: 'AIzaSyAsIAx0nM0uDri0syUHSm9eiBg3cDbl8eo',
        //     },
        // });
        app.use(VueGoogleMaps, {
            load: {
                key: 'AIzaSyAsIAx0nM0uDri0syUHSm9eiBg3cDbl8eo',
                libraries: "places"
            },
        });
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});