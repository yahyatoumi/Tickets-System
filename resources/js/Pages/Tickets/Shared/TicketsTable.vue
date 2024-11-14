<template>
    <table class="w-full whitespace-nowrap">
        <thead>
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6">Title</th>
                <th class="pb-4 pt-6 px-6">Description</th>
                <th class="pb-4 pt-6 px-6" colspan="2">
                    {{ canSeeFieldOfTicket(auth, "tech") ? 'Tech ID' : 'Status' }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="ticket in tickets.data" :key="ticket.id" v-bind:class="getClass(ticket.status)"
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
                <td class="border-t">
                    <span class="flex items-center px-6 py-4">
                        {{ canSeeFieldOfTicket(auth, "tech") ? (ticket.assigned_tech_id || "Not set") : ticket.status }}
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
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import { isAdmin, isEndUser, canSeeFieldOfTicket, canEditFieldOfTicket } from '@/helpers/rolesHelpers'

export default {
    components: {
        Head,
        Link,
    },
    computed: {
        auth() {
            return this.$page.props.auth
        },
        tickets() {
            return this.$page.props.tickets
        },
    },
    methods: {
        navigateToEdit(ticketId) {
            console.log(`/tickets/${ticketId}/edit`)
            this.$inertia.visit(`/tickets/${ticketId}/edit`);
        },
        getClass(status) {
            const colors = {
                "pending": "border-red-500",
                "in_progress": "border-green-500",
                "resolved": "border-blue-500",
            }
            return `hover:bg-gray-100 focus-within:bg-gray-100 cursor-pointer border-l-4 ${colors[status]}`
        },
        isEndUser,
        canSeeFieldOfTicket,
        canEditFieldOfTicket
    },

}
</script>