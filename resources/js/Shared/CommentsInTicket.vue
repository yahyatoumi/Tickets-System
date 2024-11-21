<template>
    <section class="py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-4">Customer Comments</h2>

            <!-- Add Comment Form -->
            <form class="mt-8 bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2">Add a Comment</h3>
                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-medium mb-2">Comment</label>
                    <textarea id="comment" name="comment" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required></textarea>
                </div>
                <files-input v-model="form.files" :error="form.errors.files" label="Upload Files"
                    class="pb-8 pr-6 w-full lg:w-1/2" />
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Post Comment
                </button>
            </form>

            <div class="space-y-4">
                <!-- Comment 1 -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center bg-gray-200 uppercase">
                            j
                        </div>
                        <div>
                            <h3 class="font-semibold">John Doe</h3>
                            <p class="text-sm text-gray-500">Posted on March 15, 2024</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Great product! I've been using it for a week now and I'm very satisfied
                        with its
                        performance.</p>
                </div>
            </div>

            
        </div>
    </section>
</template>

<script>
import FilesInput from '@/Shared/UI/FilesInput.vue'


export default {
    components: {
        FilesInput
    },
    props: {
        links: Array,
    },
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
    },
}
</script>