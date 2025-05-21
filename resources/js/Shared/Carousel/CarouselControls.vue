<template>
    <!-- in vue 2.x you need wrap these buttons in a div -->
    <button class="carousel-control left" @click="$emit('prev')">Prev</button>
    <button class="carousel-control right" @click="$emit('next')">Next</button>
</template>

<script setup>
defineEmits(["prev", "next"]);
defineProps(["size"]);
</script>
<style scoped>
.carousel-control {
    @apply bg-[rgba(0,0,0,0.5)] absolute h-[10%] w-[10%] align-middle text-[#f3f3f3] cursor-pointer border-[none] top-[40%];
}
.left {
    @apply left-0;
}
.right {
    @apply right-0;
}
</style>
