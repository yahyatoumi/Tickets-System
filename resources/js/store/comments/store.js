import { reactive } from 'vue'

export const commentsStore = reactive({
    comments: {
        data: [],
        links: {}
    },
    setComments(newComments) {
        const plainData = JSON.parse(JSON.stringify(newComments.data));
        this.comments = {
            ...newComments,
            data: plainData,
        };
    },
    addComment(newComment) {
        console.log("oldddd", this.comments.data)
        this.comments.data.unshift(newComment);
        console.log("newwwwrr", this.comments.data)
    },
    clearCommnets() {
        this.comments = { data: [], links: {} };
    },
})