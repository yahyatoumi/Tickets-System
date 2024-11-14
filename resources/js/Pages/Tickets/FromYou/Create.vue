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
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" label="Title" />
          <textarea-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Description" />
          <files-input v-model="form.files" :error="form.errors.files" label="Upload Files" class="pb-8 pr-6 w-full lg:w-1/2" />
          <!-- <div @click="show">
            showwww
          </div> -->
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create
            Organization</loading-button>
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
import TextareaInput from '@/Shared/UI/TextareaInput.vue'
import FilesInput from '@/Shared/UI/FilesInput.vue'


export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    FilesInput
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: null,
        description: null,
        files: []
      }),
    }
  },
  methods: {
    store() {
      // console.log(this.form)
      this.form.post('/tickets/fromyou/create')
    },
    show(){
      console.log("fileeee:", this.form.files)
    }
  },
}
</script>