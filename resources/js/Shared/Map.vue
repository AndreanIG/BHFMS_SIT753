<script setup>
import { watch, onMounted, reactive } from "vue";

const props = defineProps({
    lat: String,
    lng: String,
});

onMounted(() => {
    const map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: parseFloat(lat.value),
            lng: parseFloat(lng.value),
        },
        zoom: 18,
    });

    watch([() => props.lat, () => props.lng], ([lat, lng]) => {
        map.setCenter({
            lat: parseFloat(lat),
            lng: parseFloat(lng),
        });
    });
});
</script>

<template>
    <div id="map" style="width: 100%; height: 500px"></div>
</template>
