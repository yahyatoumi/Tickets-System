<template>
    <div class="group flex items-center cursor-pointer select-none relative">
        <v-icon name="md-notifications-sharp" class="text-gray-400 w-6 h-6" />
        <div v-if="$notificationsStore.count > 0"
            class="w-4 h-4 absolute -right-1 -top-1 rounded-full flex bg-red-500 text-white justify-center items-center text-[10px] font-bold">
            {{ $notificationsStore.count }}
        </div>
    </div>
</template>

<script>
import Dropdown from '@/Shared/Dropdown.vue'
import { isEndUser } from '@/helpers/rolesHelpers';
import SideBarNav from './SideBarNav.vue';
import { ref, watchEffect } from 'vue';

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
}
</script>
