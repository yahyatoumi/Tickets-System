import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { Ziggy } from './ziggy'; // Adjust path if necessary
import { route } from 'ziggy-js';
import { addIcons } from 'oh-vue-icons';
import { OhVueIcon } from 'oh-vue-icons';
import { MdExpandmore, FcMenu, IoTicket, BiChatFill, MdNotificationsSharp } from 'oh-vue-icons/icons';

import Echo from 'laravel-echo';
 
import Pusher from 'pusher-js';
window.Pusher = Pusher;

addIcons(MdExpandmore, FcMenu, IoTicket, BiChatFill, MdNotificationsSharp);

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {

    window.Echo = new Echo({
      broadcaster: 'reverb',
      key: import.meta.env.VITE_REVERB_APP_KEY,
      wsHost: import.meta.env.VITE_REVERB_HOST,
      wsPort: import.meta.env.VITE_REVERB_PORT,
      wssPort: import.meta.env.VITE_REVERB_PORT,
      forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
      enabledTransports: ['ws', 'wss'],
    });

    createApp({ render: () => h(App, props) })
      .use(plugin)
      .provide('route', route) // Make Ziggy available globally as 'route'
      .component("Link", Link)
      .component("Head", Head)
      .component('v-icon', OhVueIcon)
      .mount(el)
  },
})
