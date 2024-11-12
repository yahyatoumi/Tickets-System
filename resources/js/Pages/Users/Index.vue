<template>
    <div>

        <Head title="users" />
        <h1 class="mb-8 text-3xl font-bold">Users</h1>
        <div v-if="auth.user.role === 'admin'" class="flex items-center justify-between mb-6">
            <Link class="bg-indigo-800 px-6 py-3 font-bold text-xs rounded ml-auto text-white" href="/users/create">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;new user</span>
            </Link>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">Name</th>
                        <th class="pb-4 pt-6 px-6">Email</th>
                        <th class="pb-4 pt-6 px-6" colspan="2">Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100 cursor-pointer"
                        @click="navigateToEdit(user.id)">
                        <td class="border-t">
                            <span class="flex items-center px-6 py-4 focus:text-indigo-500">
                                {{ user.username }}
                                <icon v-if="user.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                            </span>
                        </td>
                        <td class="border-t">
                            <span class="flex items-center px-6 py-4">
                                {{ user.email || "null" }}
                            </span>
                        </td>
                        <td class="border-t">
                            <span class="flex items-center px-6 py-4">
                                {{ user.role }}
                            </span>
                        </td>
                        <td v-if="auth.user.role === 'admin'" class="w-px border-t">
                            <v-icon name="md-expandmore" class="w-6 h-6 fill-gray-400 -rotate-90" />
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination class="mt-6" :links="users.links" />
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import CustomButton from '@/Shared/UI/Button.vue'
//   import pickBy from 'lodash/pickBy'
import Layout from '@/Layouts/Layout.vue'
//   import throttle from 'lodash/throttle'
//   import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
//   import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
    components: {
        Head,
        //   Icon,
        Link,
        Pagination,
        //   SearchFilter,
    },
    layout: Layout,
    props: {
        // filters: Object,
        users: Object,
        auth: Object
    },
    data() {
        return {
            form: {
                // search: this.filters.search,
                // trashed: this.filters.trashed,
            },
        }
    },
    watch: {
        //   form: {
        //     deep: true,
        //     handler: throttle(function () {
        //       this.$inertia.get('/users', pickBy(this.form), { preserveState: true })
        //     }, 150),
        //   },
    },
    methods: {
        //   reset() {
        //     this.form = mapValues(this.form, () => null)
        //   },
        navigateToEdit(userId) {
            // Navigate to the user's edit page
            if (this.auth.user.role === 'admin')
                this.$inertia.visit(`/users/${userId}/edit`);
        },
    },

}
</script>