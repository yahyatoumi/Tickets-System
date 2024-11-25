<template>
    <!-- Add Comment Form -->
    <form @submit.prevent="store" class="mt-8 bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-2">Add a Comment</h3>
        <textarea-input v-model="form.body" :error="form.errors.body" class="pb-8  w-full"
            label="Comment" />
        <files-input v-model="form.files" :error="form.errors.files" label="Upload Files"
            class="pb-8 pr-6 w-full lg:w-1/2" />
        <button type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Post Comment
        </button>
    </form>
</template>

<script>
import FilesInput from '@/Shared/UI/FilesInput.vue'
import TextareaInput from '@/Shared/UI/TextareaInput.vue'
import { commentsStore } from '@/store/comments/store';


export default {
    components: {
        FilesInput,
        TextareaInput
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                body: null,
                files: []
            }),
        }
    },
    methods: {
        store() {
            // console.log(this.form)
            const ticketId = this.$page.props.ticket.id
            this.form.post(`/comments/${ticketId}`, {
                preserveScroll: true, // Prevent page scroll
                only: ['comments'],
                onSuccess: (ress) => {
                    this.form.reset();
                    console.log("ressss", ress.props.comments)
                    commentsStore.setComments(ress.props.comments)
                },
                onError: (errors) => {
                    console.error('Validation errors:', errors);
                },
            });
        },
    },
}
</script>