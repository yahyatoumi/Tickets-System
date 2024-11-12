import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { Ziggy } from './ziggy'; // Adjust path if necessary
import { route } from 'ziggy-js';
import { addIcons } from 'oh-vue-icons';
import { OhVueIcon } from 'oh-vue-icons';
import { MdExpandmore, FcMenu, IoTicket, BiChatFill } from 'oh-vue-icons/icons';

addIcons(MdExpandmore, FcMenu, IoTicket, BiChatFill);

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .provide('route', route) // Make Ziggy available globally as 'route'
      .component("Link", Link)
      .component("Head", Head)
      .component('v-icon', OhVueIcon)
      .mount(el)
  },
})
