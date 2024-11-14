<template>
    <table class="w-full whitespace-nowrap">
        <thead>
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6">Files</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="file in ticket.files" :key="file.id">
                <td class="border-t">
                    <a target="_blank" rel="noopener noreferrer" :href="filesSeverUrl + file.file_path"
                        class="flex items-center px-6 py-4">
                        {{ file.original_name }}
                    </a>
                </td>
            </tr>
            <tr v-if="ticket.files.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">No files found.</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
const filesSeverUrl = import.meta.env.VITE_FILES_API_URL;

export default {
    components: {
        Head,
        Link,
    },
    data() {
        return {
            filesSeverUrl: import.meta.env.VITE_FILES_API_URL
        }
    },
    computed: {
        ticket() {
            console.log("tiket", this.$page.props.ticket)
            return this.$page.props.ticket
        },
    },
    mounted() {
        console.log(filesSeverUrl);
        console.log("tiket", this.$page.props.ticket)
    }

}
</script>