<template>
    <div class="carousel-indicators">
        <button
            class="carousel-indicator-item"
            :class="{ active: currentIndex === index }"
            v-for="(item, index) in total"
            :key="index"
            @click="$emit('switch', index)"
        ></button>
    </div>
</template>

<script setup>
defineEmits(["switch"]);
defineProps(["total", "currentIndex"]);
</script>

<style scoped>
.carousel-indicators {
    @apply absolute -translate-x-2/4 z-[2] left-2/4 bottom-[1.5em];
}
.carousel-indicator-item {
    @apply w-[15px] h-[15px] opacity-50 cursor-pointer m-[0.2em] rounded-[50%] border-[none];
    background: #fff;
}
.active {
    @apply opacity-100;
}
</style>
