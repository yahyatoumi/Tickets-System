<template>
    <div class="flex items-center relative w-full">
        <multiselect @search-change="onSearchChange" v-model="selectedUser" :options="users"
            label="username" placeholder="Search for a user..." track-by="id" :searchable="true"
            @close="onClose" class="w-full bg-white rounded shadow z-10">
        </multiselect>
        <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm" type="button"
            @click="resetSearch">
            Reset
        </button>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'

export default {
    components: { Multiselect },
    props: {
        modelValue: String,
        users: Array, // Users array passed from parent
        new_assigned_tech: Object,
    },
    emits: ['update:modelValue', 'reset', 'selectUser'],
    data() {
        return {
            selectedUser: this.new_assigned_tech || null,
        };
    },
    methods: {
        onSelectUser() {
            // Emit the selected user's value to parent
            console.log("fifififififi", this.selectedUser)
            // this.$emit('update:modelValue', user ? user.username : '');
            // this.$emit('selectUser', user); // Emit the user object if needed
        },
        resetSearch() {
            this.selectedUser = {}; // Clear the selection
            this.$emit('update:modelValue', ''); // Clear the input in the parent
        },
        onSearchChange(searchValue) {
            this.$emit('update:modelValue', searchValue);
        },
        onClose() {
            console.log("closed fifififififi", this.selectedUser)
            this.$emit('selectUser', this.selectedUser || null); // Emit the user object if needed
        }
    },
};
</script>
