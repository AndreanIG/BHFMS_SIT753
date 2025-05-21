<script setup>
import Carousel from "../../Shared/Carousel/Carousel.vue";
defineProps({
    slides: Object,
});
</script>

<template>
    <h1>test 123</h1>
    <span class="align-middle">
        <Carousel
            :slides="slides"
            controls
            indicators
            interval
            size="800"
        />
    </span>
</template>
