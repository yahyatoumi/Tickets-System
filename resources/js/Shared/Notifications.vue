<template>
    <dropdown class="mt-1" placement="bottom-end">
        <template #default>
            <div class="group flex items-center cursor-pointer select-none relative">
                <v-icon name="md-notifications-sharp" class="text-gray-400 w-6 h-6" />
            </div>
        </template>
        <template #dropdown>
            <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                <Link v-for="notification in notifications" :key="notification.id"
                    class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2 w-64"
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


export default {
    components: {
        Dropdown,
        SideBarNav
    },
    computed: {
        auth() {
            return this.$page.props.auth
        },
    },
    methods: {
        isEndUser,
    },
    mounted() {
        Echo.private(`channel-name`)
            .listen("MessageSent", (response) => {
                messages.value.push(response.message);
            })
            .listenForWhisper("typing", (response) => {
                isUserTyping.value = response.userID === props.user.id;

                if (isUserTypingTimer.value) {
                    clearTimeout(isUserTypingTimer.value);
                }

                isUserTypingTimer.value = setTimeout(() => {
                    isUserTyping.value = false;
                }, 1000);
            });
        console.log("notifications: ", this.notifications);
    },

}
</script>
