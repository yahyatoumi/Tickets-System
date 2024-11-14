<template>
    <div :class="$attrs.class">
        <div class="w-full h-32 bg-green-200 relative">
            <label v-if="label"
                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                :for="id">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                            upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">(MAX. 10MB)</p>

                </div>
            </label>
            <!-- Invisible file input, but the div is clickable and draggable -->
            <input :readonly="$attrs.readonly || false" :id="id" ref="input" v-bind="{ ...$attrs, class: null }"
                class="absolute inset-0 opacity-0 z-0 cursor-pointer" :class="{ error: error }" type="file"
                :multiple="true" @change="handleFileChange" />
        </div>
        <div v-if="error" class="form-error">{{ error }}</div>

        <!-- File Previews -->
        <div v-if="modelValue.length" class="file-preview">
            <ul>
                <li v-for="(file, index) in modelValue" :key="index" class="file-preview-item">
                    <div v-if="file.preview && file.type.startsWith('image/')">
                        <img :src="file.preview" alt="preview" class="file-preview-image" />
                    </div>
                    <span>{{ file.name }}</span>
                    <button type="button" @click="removeFile(index)" class="remove-file-btn">Remove</button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `file-input-${uuid()}`
            },
        },
        label: String,
        error: String,
        modelValue: {
            type: Array, // Expect an array of file objects
            default: () => [],
        },
    },
    emits: ['update:modelValue'],
    methods: {
        // Handles the file change event
        handleFileChange(event) {
            const files = Array.from(event.target.files);
            const updatedFiles = [...this.modelValue];

            files.forEach(file => {
                // Check if the file already exists in modelValue (by name or content)
                const isDuplicate = updatedFiles.some(existingFile => existingFile.name === file.name && existingFile.size === file.size);

                // Only add the file if it's not a duplicate
                if (!isDuplicate) {
                    if (file.type.startsWith('image/')) {
                        file.preview = URL.createObjectURL(file); // Generate a preview URL for image files
                    }
                    updatedFiles.push(file);
                }
            });

            // Update modelValue with the new files (after checking for duplicates)
            this.$emit('update:modelValue', updatedFiles);
        },

        // Remove a file from the list
        removeFile(index) {
            const updatedFiles = [...this.modelValue];
            updatedFiles.splice(index, 1); // Remove the file at the given index
            this.$emit('update:modelValue', updatedFiles);
        },

        // Focus on the file input
        focus() {
            this.$refs.input.focus();
        },

        // Select all files in the input
        select() {
            this.$refs.input.select();
        },

        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
    },
    watch: {
        // Clean up preview URLs when the component is destroyed or when modelValue changes
        modelValue(newFiles) {
            newFiles.forEach(file => {
                if (file.preview) {
                    URL.revokeObjectURL(file.preview); // Release the object URL after use
                }
            });
        }
    },
}
</script>

<style scoped>
.file-preview ul {
    list-style-type: none;
    padding-left: 0;
}

.file-preview li {
    margin: 5px 0;
    display: flex;
    align-items: center;
}

.file-preview-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.file-preview-image {
    max-width: 100px;
    max-height: 100px;
    margin-right: 10px;
}

.remove-file-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 2px 8px;
    cursor: pointer;
}

.remove-file-btn:hover {
    background-color: darkred;
}
</style>