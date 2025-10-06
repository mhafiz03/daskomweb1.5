import { getCurrentInstance } from 'vue';
import { toast as toastApi } from 'vue3-toastify';

export function useToast() {
    const toast = {
        success: (message, options) => toastApi.success(message, options),
        error: (message, options) => toastApi.error(message, options),
        info: (message, options) => toastApi.info(message, options),
        warning: (message, options) => toastApi.warning(message, options),
    };

    const instance = getCurrentInstance();
    if (instance?.proxy) {
        instance.proxy.toast = toast;
    }

    return toast;
}
