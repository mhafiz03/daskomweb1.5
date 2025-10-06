import { getCurrentInstance } from 'vue';
import { toast as toastApi } from 'vue3-toastify';

export function useToast() {
    const toast = {
        success: (message, options) => {
            console.log('Success:', message);
            toastApi.success(message, options);
        },
        error: (message, options) => {
            console.log('Error:', message);
            toastApi.error(message, options);
        },
        info: (message, options) => {
            console.log('Info:', message);
            toastApi.info(message, options);
        },
        warning: (message, options) => {
            console.log('Warning:', message);
            toastApi.warning(message, options);
        },
    };

    const instance = getCurrentInstance();
    if (instance?.proxy) {
        instance.proxy.toast = toast;
    }

    return toast;
}
