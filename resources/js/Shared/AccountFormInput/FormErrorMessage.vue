<script setup>
defineProps({
    errorMessage: String,
});
</script>

<template>
    <div
        v-if="errorMessage"
        v-text="errorMessage"
        class="text-red-500 text-xs mt-1"
    />
</template>
