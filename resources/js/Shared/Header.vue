<template>
    <header class="md:flex md:shrink-0">
        <div class="flex items-center justify-between px-6 py-4 bg-indigo-900 md:shrink-0 md:justify-center md:w-56">
            <div>logo</div>
            <dropdown class="md:hidden" placement="bottom-end">
                <template #default>
                    <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </template>
                <template #dropdown>
                    <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                        <side-bar-nav />
                    </div>
                </template>
            </dropdown>
        </div>
        <div class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">
            <div></div>
            <dropdown class="mt-1" placement="bottom-end">
                <template #default>
                    <div class="group flex items-center cursor-pointer select-none">
                        <div
                            class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                            <span>
                                {{ auth.user.username }}
                            </span>
                            <!-- <span class="hidden md:inline">&nbsp;{{ props.auth.user.username }}</span> -->
                        </div>
                        <v-icon name="md-expandmore" class="" />
                    </div>
                </template>
                <template #dropdown>
                    <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                        <Link class="block px-6 py-2 hover:text-white hover:bg-indigo-500" href="/users">Manage Users
                        </Link>
                        <Link v-if="!isEndUser(auth)"
                            class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2"
                            href="/users">
                        <span>
                            From you
                        </span>
                        <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
                        </Link>
                        <Link v-if="!isEndUser(auth)"
                            class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2"
                            href="/users">
                        <span>
                            To you
                        </span>
                        <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
                        </Link>
                        <Link v-if="isEndUser(auth)"
                            class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2"
                            href="/users">
                        <span>
                            Tickets
                        </span>
                        <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
                        </Link>
                        <Link class="group px-6 py-2 hover:text-white hover:bg-indigo-500 flex items-center gap-2"
                            href="/chat">
                        <span>
                            Chat
                        </span>
                        <v-icon name="bi-chat-fill" class="fill-gray-400 group-hover:fill-white" />
                        </Link>
                        <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                            href="/logout" method="delete" as="button">Logout</Link>
                    </div>
                </template>
            </dropdown>
        </div>
    </header>
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
        }
    },
    methods: {
        isEndUser
    }

}
</script>
