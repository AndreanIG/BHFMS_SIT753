<script setup>
import CarouselItem from "./CarouselItem.vue";
import CarouselControls from "./CarouselControls.vue";
import CarouselIndicators from "./CarouselIndicators.vue";
import { onMounted, onBeforeUnmount, reactive } from "vue";

const props = defineProps({
    slides: {
        type: Array,
        required: true,
    },
    controls: {
        type: Boolean,
        default: false,
    },
    indicators: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
    },
    interval: {
        type: Boolean,
        default: false,
    },
    interval_val: {
        type: Number,
        default: 5000,
    },
});

const data = reactive({
    currentSlide: 0,
    slideInterval: null,
    direction: "right",
    // size: props.size,
});

const setCurrentSlide = (index) => {
    data.currentSlide = index;
};

const prev = (step = -1) => {
    const index =
        data.currentSlide > 0
            ? data.currentSlide + step
            : props.slides.length - 1;
    setCurrentSlide(index);
    data.direction = "left";
    startSlideTimer();
};

const _next = (step = 1) => {
    const index =
        data.currentSlide < props.slides.length - 1
            ? data.currentSlide + step
            : 0;
    setCurrentSlide(index);
    data.direction = "right";
};

const next = (step = 1) => {
    _next(step);
    startSlideTimer();
};

const stopSlideTimer = () => {
    clearInterval(data.slideInterval);
};

const startSlideTimer = () => {
    stopSlideTimer();
    if (props.interval) {
        data.slideInterval = setInterval(() => {
            _next();
        }, props.interval_val);
    }
};

const switchSlide = (index) => {
    const step = index - data.currentSlide;
    if (step > 0) {
        next(step);
    } else {
        prev(step);
    }
};

onMounted(() => {
    startSlideTimer();
});

onBeforeUnmount(() => {
    stopSlideTimer();
});
</script>

<template>
    <div class="flex items-center justify-center">
        <div
            class="relative w-1/2 h-[60vh] overflow-hidden object-cover rounded-md"
        >
            <carousel-indicators
                v-if="props.indicators"
                :total="props.slides.length"
                :current-index="data.currentSlide"
                @switch="switchSlide($event)"
            ></carousel-indicators>
            <carousel-item
                v-for="(slide, index) in props.slides"
                :slide="slide"
                :key="`item-${index}`"
                :current-slide="data.currentSlide"
                :index="index"
                :direction="data.direction"
                @mouseenter="stopSlideTimer()"
                @mouseout="startSlideTimer()"
            >
                {{ slide }}
            </carousel-item>
            <carousel-controls
                v-if="props.controls"
                @prev="prev"
                @next="next"
            ></carousel-controls>
        </div>
    </div>
</template>
