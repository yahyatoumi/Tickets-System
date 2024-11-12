<template>
    <div class="flex items-center">
        <div class="flex w-full bg-white rounded shadow">
            <input
                class="relative px-6 py-3 w-full rounded-r focus:shadow-outline"
                autofocus
                autocomplete="off"
                type="text"
                :value="modelValue"
                @input="onInput"
                placeholder="Search…"
            />
        </div>
        <button
            class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm"
            type="button"
            @click="resetSearch"
        >
            Reset
        </button>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: String,
        maxWidth: {
            type: Number,
            default: 300,
        },
    },
    emits: ['update:modelValue', 'reset'],
    methods: {
        onInput(event) {
            this.$emit('update:modelValue', event.target.value);
        },
        resetSearch() {
            this.$emit('update:modelValue', ''); // Clear search
            this.$emit('reset'); // Emit reset event
        },
    },
};
</script>
