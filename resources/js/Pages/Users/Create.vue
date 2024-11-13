<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.username" :error="form.errors.username" class="pb-8 pr-6 w-full lg:w-1/2" label="Username" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <select-input v-model="form.role" :error="form.errors.role" class="pb-8 pr-6 w-full lg:w-1/2" label="Role">
            <option value="admin">Admin</option>
            <option value="supervisor">Supervisor</option>
            <option value="end_user">End user</option>
          </select-input>
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" label="password" />
          <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="pb-8 pr-6 w-full lg:w-1/2" type="password" label="Confirm password" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Organization</loading-button>
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        username: null,
        email: null,
        role: null,
        password: null,
        password_confirmation: null,
      }),
    }
  },
  methods: {
    store() {
      // console.log(this.form)
      this.form.post('/users/create')
    },
  },
}
</script>
