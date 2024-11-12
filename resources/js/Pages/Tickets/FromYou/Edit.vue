<template>
    <div>

        <Head :title="'Ticket - ' + form.title + ' - From You'" />
        <h1 class="mb-8 text-3xl font-bold">
            <Link class="text-indigo-400 hover:text-indigo-600" href="/tickets/fromyou">Tickets</Link>
            <span class="text-indigo-400 font-medium">/</span>
            {{ form.title }}
        </h1>
        <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="update">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Title *" />
                    <textarea-input v-model="form.description" :error="form.errors.description"
                        class="pb-8 pr-6 w-full lg:w-1/2" label="description" />
                    <div class="flex flex-col w-full pb-8">
                        <div v-if="auth.user.role !== 'end_user'">
                            Submitter id:
                            <span class="ml-2">{{ submitter_id }}</span>
                        </div>
                        <div v-if="auth.user.role !== 'end_user'" class="mt-4">
                            Tech id:
                            <span class="ml-2">{{ assigned_tech_id }}</span>

                            <span @click="showEdit = true"
                                class="ml-2 underline hover:text-blue-500 text-xs cursor-pointer">edit</span>
                        </div>
                        <search-input v-if="showEdit" v-model="searchForm.value" class="mr-4 w-full max-w-md mt-4"
                            @reset="reset" />
                    </div>
                </div>
                <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete
                        user</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
                        user</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import TextInput from '@/Shared/UI/TextInput.vue'
import SelectInput from '@/Shared/UI/SelectInput.vue'
import LoadingButton from '@/Shared/UI/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import TextareaInput from '@/Shared/UI/TextareaInput.vue'
import SearchInput from '@/Shared/UI/SearchInput.vue'
import { throttle } from 'lodash'



export default {
    components: {
        Head,
        Link,
        LoadingButton,
        SelectInput,
        TextInput,
        TrashedMessage,
        TextareaInput,
        SearchInput
    },
    layout: Layout,
    props: {
        ticket: Object,
        auth: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                title: this.ticket.title,
                description: this.ticket.description,
            }),
            submitter_id: this.ticket.submitter_id,
            assigned_tech_id: this.ticket.assigned_tech_id || "Not set",
            showEdit: false,
            searchForm: {
                value: ''
            }
        }
    },
    watch: {
        searchForm: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(`/users/search?query=${this.searchForm.value}`, { preserveState: true })
            }, 150),
        },
    },
    methods: {
        update() {
            this.form.put(`/users/${this.ticket.id}`)
        },
        destroy() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.$inertia.delete(`/users/${this.ticket.id}`)
            }
        },
    },
}
</script>