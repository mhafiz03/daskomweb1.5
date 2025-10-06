import anime from 'animejs/lib/anime.es.js';
import jQuery from 'jquery';
import Vue3Toastify, { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faExclamationCircle,
    faCheckCircle,
    faUserEdit,
    faSignOutAlt,
    faCode,
    faFileCode,
    faListAlt,
    faHistory,
    faChartArea,
    faEnvelopeOpen,
    faArrowCircleDown,
    faEnvelope,
    faWindowMaximize,
    faWindowClose,
    faPaperPlane,
    faPhone,
    faAddressCard,
    faAt,
    faChalkboardTeacher,
    faPlus,
    faTrash,
    faPen,
    faBook,
    faCaretDown,
    faCaretUp,
    faCheck,
    faTimes,
    faCaretLeft,
    faCaretRight,
    faCalendarAlt,
    faCog,
    faBookOpen,
    faQrcode,
    faClipboardCheck,
    faRadiationAlt,
    faUsers,
    faHome,
    faStar,
    faFileMedicalAlt,
    faLock,
    faUnlock,
    faUnlockAlt,
    faTasks,
    faSort,
    faWindowMinimize,
    faArrowLeft,
    faArrowRight,
    faArrowUp,
    faArrowDown,
} from '@fortawesome/free-solid-svg-icons';
import { faLine, faInstagram, faYoutube } from '@fortawesome/free-brands-svg-icons';

import StarRating from '../components/StarRating.vue';
import ToggleSwitch from '../components/ToggleSwitch.vue';
import ChartView from '../components/ChartView.vue';

library.add(
    faExclamationCircle,
    faCheckCircle,
    faUserEdit,
    faSignOutAlt,
    faCode,
    faFileCode,
    faListAlt,
    faHistory,
    faChartArea,
    faEnvelopeOpen,
    faArrowCircleDown,
    faEnvelope,
    faWindowMaximize,
    faWindowClose,
    faPaperPlane,
    faPhone,
    faAddressCard,
    faUsers,
    faAt,
    faChalkboardTeacher,
    faPlus,
    faTrash,
    faPen,
    faBook,
    faCaretDown,
    faCaretUp,
    faCheck,
    faTimes,
    faCaretLeft,
    faCaretRight,
    faCalendarAlt,
    faCog,
    faBookOpen,
    faClipboardCheck,
    faRadiationAlt,
    faQrcode,
    faHome,
    faStar,
    faFileMedicalAlt,
    faLock,
    faUnlock,
    faUnlockAlt,
    faTasks,
    faSort,
    faWindowMinimize,
    faArrowLeft,
    faArrowRight,
    faArrowUp,
    faArrowDown,
    faLine,
    faInstagram,
    faYoutube,
);

dom.watch();

function registerToastHelpers(app) {
    const defaultError = "Maaf, telah terjadi sesuatu\n(Panggil CEN untuk dilihat lebih lanjut)";
    const defaultSuccess = 'Proses telah berhasil';

    /**
     * @param {unknown} payload
     * @param {string} fallback
     */
    const getMessage = (payload, fallback) => {
        if (!payload || typeof payload !== 'object') {
            return fallback;
        }

        if ('message' in payload && typeof payload.message === 'string') {
            return payload.message;
        }

        return fallback;
    };

    app.config.globalProperties.$toasted = {
        global: {
            showError(payload = {}) {
                app.config.globalProperties.$toast?.error(getMessage(payload, defaultError), {
                    icon: ['fas', 'exclamation-circle'],
                });
            },
            showSuccess(payload = {}) {
                app.config.globalProperties.$toast?.success(getMessage(payload, defaultSuccess), {
                    icon: ['fas', 'check-circle'],
                });
            },
        },
    };
}

function registerScrollbarDirective(app) {
    app.directive('scrollbar', {
        mounted(el) {
            el.classList.add('overflow-y-auto');
            el.classList.add('scrollbar-thin');
            el.classList.add('scrollbar-track-transparent');
            el.classList.add('scrollbar-thumb-slate-400');
        },
    });
}

function registerScrollDirective(app) {
    app.directive('scroll', {
        mounted(el, binding) {
            const handler = (event) => {
                if (typeof binding.value === 'function') {
                    binding.value(event, el);
                }
            };

            el.__scrollHandler__ = handler;
            el.addEventListener('scroll', handler, { passive: true });
        },
        unmounted(el) {
            if (el.__scrollHandler__) {
                el.removeEventListener('scroll', el.__scrollHandler__);
                delete el.__scrollHandler__;
            }
        },
    });
}

export default function installUi(app) {
    window.$ = window.jQuery = jQuery;

    app.use(Vue3Toastify, { autoClose: 3000, position: 'top-right' });
    registerToastHelpers(app);
    registerScrollbarDirective(app);
    registerScrollDirective(app);

    app.config.globalProperties.$anime = anime;

    app.component('FontAwesomeIcon', FontAwesomeIcon);
    app.component('StarRating', StarRating);
    app.component('ToggleButton', ToggleSwitch);
    app.component('Chart', ChartView);
}
