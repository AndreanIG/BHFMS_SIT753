<script setup>
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    searchResults: Object,
});
</script>

<template>
    <Header />
    <section
        v-if="props.searchResults.length > 0"
        class="mx-2 my-2 space-y-2 min-h-[65vh]"
    >
        <div class="text-2xl font-semibold">Showing All Boarding House</div>
        <div class="text-md text-gray-600">
            List of all of our registered boarding house
        </div>
        <div class="flex flex-wrap">
            <div
                v-for="boardingHouse in searchResults"
                class="px-1 my-2 w-1/6 h-1/3"
            >
                <div class="bg-gray-200">
                    <img
                        :src="boardingHouse.imageUrl"
                        class="w-full h-80 object-cover"
                        alt="No Image"
                    />
                    <div class="h-2/3 space-y-1 p-2">
                        <div class="text-sm text-gray-800">
                            {{ boardingHouse.distance.toFixed(2) }} kilometers from destination
                        </div>
                        <div class="font-semibold">
                            {{ boardingHouse.boarding_name }}
                        </div>
                        <div class="text-xs text-gray-600 truncate">
                            {{ boardingHouse.address }}
                        </div>
                        <div class="text-sm box-content truncate">
                            {{ boardingHouse.boarding_desc }}
                        </div>
                        <Link
                            href="/boarding/detail"
                            :data="{ id: boardingHouse.id }"
                            class="text-xs flex items-center font-semibold"
                        >
                            VIEW MORE DETAIL
                            <div class="ml-1 text-sm">>></div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        v-else
        class="mx-2 my-2 space-y-2 min-h-[60vh] flex flex-col justify-center items-center space-y-4"
    >
        <div class="text-3xl">
            😥 No Result
        </div>
        <div>

            We have searched our entire registry and could not find what you were looking for.
        </div>
    </section>
    <Footer />
</template>
