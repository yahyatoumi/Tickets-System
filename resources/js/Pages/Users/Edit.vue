<template>
    <div>

        <Head :title="form.name" />
        <h1 class="mb-8 text-3xl font-bold">
            <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
            <span class="text-indigo-400 font-medium">/</span>
            {{ form.username }}
        </h1>
        <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="update">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input v-model="form.username" :error="form.errors.username" class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Username *" />
                    <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Email" />
                    <select-input v-model="form.role" :error="form.errors.role" class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Role *">
                        <option :value="null" />
                        <option value="admin">Admin</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="end_user">End user</option>
                    </select-input>
                </div>
                <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <button class="text-red-600 hover:underline" tabindex="-1"
                        type="button" @click="destroy">Delete user</button>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
                        user</loading-button>
                </div>
            </form>
        </div>
        <!-- <h2 class="mt-12 text-2xl font-bold">Contacts</h2> -->
        <!-- <div class="mt-6 bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">Name</th>
                        <th class="pb-4 pt-6 px-6">City</th>
                        <th class="pb-4 pt-6 px-6" colspan="2">Phone</th>
                    </tr>
                </thead>
                <tr v-for="contact in organization.contacts" :key="contact.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4 focus:text-indigo-500"
                            :href="`/contacts/${contact.id}/edit`">
                        {{ contact.name }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
                        {{ contact.city }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
                        {{ contact.phone }}
                        </Link>
                    </td>
                    <td class="w-px border-t">
                        <Link class="flex items-center px-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
                        </Link>
                    </td>
                </tr>
                <tr v-if="organization.contacts.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">No contacts found.</td>
                </tr>
            </table>
        </div> -->
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import TextInput from '@/Shared/UI/TextInput.vue'
import SelectInput from '@/Shared/UI/SelectInput.vue'
import LoadingButton from '@/Shared/UI/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
    components: {
        Head,
        Link,
        LoadingButton,
        SelectInput,
        TextInput,
        TrashedMessage,
    },
    layout: Layout,
    props: {
        user: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                username: this.user.username,
                email: this.user.email,
                role: this.user.role,
            }),
        }
    },
    methods: {
        update() {
            this.form.put(`/users/${this.user.id}`)
        },
        destroy() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.$inertia.delete(`/users/${this.user.id}`)
            }
        },
    },
}
</script>