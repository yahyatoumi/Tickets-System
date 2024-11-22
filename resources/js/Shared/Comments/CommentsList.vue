<template>
    <div class="space-y-4 mt-4">
        <!-- Comment 1 -->
        <div v-for="comment in comments.data" :key="comment.id" class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center bg-gray-200 uppercase">
                    {{ comment.user.username.charAt(0) }}
                </div>
                <div>
                    <h3 class="font-semibold">{{ comment.user.username }}</h3>
                    <p class="text-sm text-gray-500">Posted on {{ formatCommentDate(comment.created_at) }}</p>
                </div>
            </div>
            <p class="text-gray-700">{{ comment.body }}</p>
        </div>
    </div>
</template>

<script>
import { formatCommentDate } from "@/helpers/dateHelpers.js"

export default {
    computed: {
        auth() {
            return this.$page.props.auth
        },
        comments() {
            return this.$page.props.comments
        }
    },
    mounted() {
        console.log("mmmmoooounted", this.comments, this.auth)
    },
    methods:{
        formatCommentDate,
    }
}
</script>