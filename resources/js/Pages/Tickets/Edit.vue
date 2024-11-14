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
                    <text-input :readonly="!canEditFieldOfTicket(auth, 'title', submitter.id)" v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Title *" />
                    <textarea-input :readonly="!canEditFieldOfTicket(auth, 'description', submitter.id)" v-model="form.description" :error="form.errors.description"
                        class="pb-8 pr-6 w-full lg:w-1/2" label="description" />
                    <select-input :disabled="!canEditFieldOfTicket(auth, 'status', submitter.id)" v-model="form.status" :error="form.errors.status"
                        class="pb-8 pr-6 w-full lg:w-1/2" label="Status">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In progress</option>
                        <option value="resolved">Resolved</option>
                    </select-input>
                    <div v-if="canSeeFieldOfTicket(auth, 'submitter')" class="flex flex-col w-full pb-8">
                        <div>
                            Submitter:
                            <span class="ml-2">{{ submitter.username }}</span>
                        </div>
                        <div v-if="isAdmin(auth)">
                            <div class="mt-4">
                                Tech:
                                <span class="ml-2 text-blue-500">{{ assigned_tech?.username || "Not set" }}</span>

                                <span @click="showEdit = true"
                                    class="ml-2 underline hover:text-blue-500 text-xs cursor-pointer">edit</span>
                            </div>
                            <search-input @selectUser="handleSelectUser" v-if="showEdit" v-model="searchForm.value"
                                :users="users?.data || []" :new_assigned_tech="new_assigned_tech"
                                class="mr-4 w-full max-w-md mt-4" @reset="reset" />
                        </div>
                        <div v-if="new_assigned_tech" class="text-xs">
                            Click update to assign it to:
                            <span class="text-green-500 text-base">
                                {{ new_assigned_tech.username }}
                            </span>
                        </div>
                    </div>
                    <files-in-edit />
                </div>
                <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete
                        ticket</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
                        ticket</loading-button>
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
import axios from 'axios'
import { isAdmin, isEndUser, canSeeFieldOfTicket, canEditFieldOfTicket } from '@/helpers/rolesHelpers'
import { isSupervisor } from '@/helpers/rolesHelpers'
import FilesInEdit from "./Shared/FilesInEdit.vue"




export default {
    components: {
        Head,
        Link,
        LoadingButton,
        SelectInput,
        TextInput,
        TrashedMessage,
        TextareaInput,
        SearchInput,
        FilesInEdit
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
                status: this.ticket.status,
                assigned_tech_id: this.ticket.assigned_tech?.id || null,
            }),
            submitter: this.ticket.submitter,
            assigned_tech: this.ticket.assigned_tech || "Not set",
            showEdit: false,
            searchForm: {
                value: ''
            },
            users: { data: [] },
            new_assigned_tech: null,
        }
    },
    watch: {
        searchForm: {
            deep: true,
            handler: throttle(async function () {
                try {
                    if (this.searchForm?.value?.trim().length) {

                        const response = await axios.get(`/users/search?query=${this.searchForm.value}`)
                        console.log('search results', response.data)

                        this.users = response.data.users // Set the response data to users
                    }
                } catch (error) {
                    console.error('Error fetching search results', error)
                }
            }, 150),
        },
    },
    methods: {
        update() {
            if (isEndUser(this.auth)) {
                delete this.form.status;
                delete this.form.assigned_tech_id;
            }
            else if (isAdmin(this.auth)) {
                !canEditFieldOfTicket(this.auth, "title", this.submitter.id) && delete this.form.title;
                !canEditFieldOfTicket(this.auth, "description", this.submitter.id) && delete this.form.description;
                this.form.assigned_tech_id = this.new_assigned_tech?.id || this.form.assigned_tech_id
            }
            else if (isSupervisor(this.auth)) {
                delete this.form.title;
                delete this.form.description;
                delete this.form.assigned_tech_id;
            }
            this.form.put(`/ticket/${this.ticket.id}`)
        },
        destroy() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.$inertia.delete(`/ticket/${this.ticket.id}`)
            }
        },
        handleSelectUser(newTech) {
            this.new_assigned_tech = newTech;
            this.showEdit = false;
            console.log('Received new new_assigned_tech_id:', newTech);
        },
        isAdmin,
        isEndUser,
        canSeeFieldOfTicket,
        canEditFieldOfTicket
    },
}
</script>