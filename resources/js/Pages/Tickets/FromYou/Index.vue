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
            <tickets-table/>
        </div>
        <pagination class="mt-6" :links="tickets.links" />
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import TicketsTable from "../Shared/TicketsTable.vue"

export default {
    components: {
        Head,
        Link,
        Pagination,
        TicketsTable,
    },
    layout: Layout,
    props: {
        tickets: Object,
        auth: Object
    },
    methods: {
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