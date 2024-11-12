<template>
   <h1>abouttt</h1>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

// Accessing page props
const { props } = usePage();
console.log("valueeee", props.value)
</script>