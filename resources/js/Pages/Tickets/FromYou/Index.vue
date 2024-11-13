<template>
    <div>

        <Head title="Tickets - From You" />
        <h1 class="mb-8 text-3xl font-bold">Tickets</h1>
        <div class="flex items-center justify-between mb-6">
            <Link class="bg-indigo-800 px-6 py-3 font-bold text-xs rounded ml-auto text-white"
                href="/tickets/fromyou/create">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;new ticket</span>
            </Link>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">Title</th>
                        <th class="pb-4 pt-6 px-6">Description</th>
                        <th v-if="auth.user.role !== 'end_user'" class="pb-4 pt-6 px-6" colspan="2">Tech id</th>
                        <th v-if="auth.user.role === 'end_user'" class="pb-4 pt-6 px-6" colspan="2">status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ticket in tickets.data" :key="ticket.id"
                        v-bind:class="getClass(ticket.status)"
                        @click="navigateToEdit(ticket.id)">
                        <td class="border-t">
                            <span class="flex items-center px-6 py-4 focus:text-indigo-500">
                                {{ ticket.title }}
                            </span>
                        </td>
                        <td class="border-t">
                            <span class="flex items-center px-6 py-4">
                                {{ ticket.description }}
                            </span>
                        </td>
                        <td v-if="auth.user.role !== 'end_user'" class="border-t">
                            <span class="flex items-center px-6 py-4">
                                {{ ticket.assigned_tech_id || "Not set" }}
                            </span>
                        </td>
                        <td v-if="auth.user.role === 'end_user'" class="border-t">
                            <span class="flex items-center px-6 py-4">
                                {{ ticket.status }}
                            </span>
                        </td>
                        <td class="w-px border-t">
                            <v-icon name="md-expandmore" class="w-6 h-6 fill-gray-400 -rotate-90" />
                        </td>
                    </tr>
                    <tr v-if="tickets.data.length === 0">
                        <td class="px-6 py-4 border-t" colspan="4">No tickets from you found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination class="mt-6" :links="tickets.links" />
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
        tickets: Object,
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
        navigateToEdit(ticketId) {
            console.log(`/tickets/fromyou/${ticketId}/edit`)
            this.$inertia.visit(`/tickets/fromyou/${ticketId}/edit`);
        },
        getClass(status) {
            const colors = {
                "pending": "border-red-500",
                "in_progress": "border-green-500",
                "resolved": "border-blue-500",
            }
            return `hover:bg-gray-100 focus-within:bg-gray-100 cursor-pointer border-l-4 ${colors[status]}`
        }
    },

}
</script>