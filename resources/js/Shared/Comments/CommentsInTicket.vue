<template>
    <section class="py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-4">Customer Comments</h2>

            <!-- Add Comment Form -->
            <comments-form />

            <!-- Comment List -->
            <comments-list />
            
        </div>
    </section>
</template>

<script>
import CommentsList from '@/Shared/Comments/CommentsList.vue'
import CommentsForm from '@/Shared/Comments/CommentsForm.vue'


export default {
    components: {
        CommentsList,
        CommentsForm
    },
}
</script>