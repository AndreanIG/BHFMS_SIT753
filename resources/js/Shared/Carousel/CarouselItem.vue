<template>
    <transition :name="transitionEffect">
        <div
            class="carousel-item"
            v-show="currentSlide === index"
            @mouseenter="$emit('mouseenter')"
            @mouseout="$emit('mouseout')"
        >
            <img :src="slide" class="object-cover" />
        </div>
    </transition>
</template>

<script setup>
defineEmits(["mouseenter", "mouseout"]);
defineProps({
    slide: Array,
    currentSlide: Number,
    index: Number,
    direction: String,
})

const transitionEffect = () => {
    return this.direction === "right" ? "slide-out" : "slide-in";
};

</script>

<style scoped>
.carousel-item {
    @apply w-full h-full absolute inset-0;
    /* align-items: center; */
}
.slide-in-enter-active,
.slide-in-leave-active,
.slide-out-enter-active,
.slide-out-leave-active {
    @apply transition-all duration-[1s] ease-[ease-in-out];
}
.slide-in-enter-from {
    @apply -translate-x-full;
}
.slide-in-leave-to {
    @apply translate-x-full;
}
.slide-out-enter-from {
    @apply translate-x-full;
}
.slide-out-leave-to {
    @apply -translate-x-full;
}
img {
    @apply w-full h-full;
    /* position: absolute; */
    /* max-width: 100%; */
    /* height: auto; */
}
</style>
