<template>
    <button :class="['custom-button', customClass]" :style="customStyle" @click="handleClick">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'CustomButton',
    props: {
        customClass: {
            type: String,
            default: '',
        },
        customStyle: {
            type: Object,
            default: () => ({}),
        },
        onClick: {
            type: Function,
            default: null,
        },
    },
    methods: {
        handleClick() {
            if (this.onClick) {
                this.onClick();
            }
        },
    },
};
</script>