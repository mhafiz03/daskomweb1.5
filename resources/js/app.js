import './bootstrap';
import '../css/app.css';
import '../css/legacy.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import axios from 'axios';
import installUi from './plugins/ui';

import { useToast } from '@/composables/useToast';

InertiaProgress.init({ color: '#1f2937' });

createInertiaApp({
    title: (title) => (title ? `${title} | Daskom` : 'Daskom'),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props),
        });

        app.use(plugin);
        installUi(app);

        app.config.globalProperties.$axios = axios;
        app.provide('axios', axios);

        app.mount(el);
        
        (function () {
            const t = useToast();
            window.$toasted = {
                global: {
                    showSuccess: (message) => t.success(message),
                    showError: (message) => t.error(message),
                    showInfo: (message) => t.info(message),
                    showWarning: (message) => t.warning(message),
                }
            };
        })();
    },
});
