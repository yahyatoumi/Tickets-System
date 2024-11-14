<template>
    <dropdown class="mt-1" placement="bottom-end">
        <template #default>
            <div class="group flex items-center cursor-pointer select-none relative">
                <div
                    class="w-4 h-4 absolute -right-1 -top-1 rounded-full flex bg-red-500 text-white justify-center items-center text-[10px] font-bold">
                    2
                </div>
                <v-icon name="md-notifications-sharp" class="text-gray-400 w-6 h-6" />
            </div>
        </template>
        <template #dropdown>
            <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                <Link class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2 w-64"
                    href="/users">
                <span>
                    ticket `please help with this` is done
                </span>
                <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
                </Link>
            </div>
        </template>
    </dropdown>
</template>

<script>
import Dropdown from '@/Shared/Dropdown.vue'
import { isEndUser } from '@/helpers/rolesHelpers';
import SideBarNav from './SideBarNav.vue';
import Pusher from 'pusher-js';
const pusherAppKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherAppCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;


export default {
    components: {
        Dropdown,
        SideBarNav
    },
    computed: {
        auth() {
            return this.$page.props.auth
        }
    },
    methods: {
        isEndUser,
        initPusher() {
            this.pusher = new Pusher(pusherAppKey, {
                cluster: pusherAppCluster,
            });
            this.channel = this.pusher.subscribe(`notifications.${this.auth.user.id}`);
            this.channel.bind('NewNotification', (data) => {
                this.notifications.unshift(data.notification);
            });
        },
    },
    mounted() {
        this.initPusher();
    },

}
</script>
