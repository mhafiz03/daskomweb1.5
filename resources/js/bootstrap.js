import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import { io } from 'socket.io-client';

const echoHost = import.meta.env.VITE_ECHO_HOST ?? window.location.hostname;
const echoPort = import.meta.env.VITE_ECHO_PORT ?? '6001';

window.io = io;
window.Echo = new Echo({
  broadcaster: 'socket.io',
  client: io,
  host: `${echoHost}:${echoPort}`,
  transports: ['websocket'],
  reconnectionAttempts: 5,
  withCredentials: true,
  // auth: { headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content } },
});
