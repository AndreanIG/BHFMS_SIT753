<script setup>
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    allBoardingHouse: Object,
});
</script>

<template>
    <Header />
    <section class="mx-2 my-2 space-y-2">
        <div class="text-2xl font-semibold">Showing All Boarding House</div>
        <div
            v-if="$page.props.flash.message"
            class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3"
            role="alert"
        >
            <svg
                class="fill-current w-4 h-4 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <path
                    d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"
                />
            </svg>
            <p>{{ $page.props.flash.message }}</p>
        </div>
        <div class="text-md text-gray-600">
            List of all of our registered boarding house
        </div>
        <div class="flex flex-wrap">
            <div
                v-for="boardingHouse in allBoardingHouse.data"
                class="px-1 my-2 w-1/6 h-1/3"
            >
                <div class="bg-gray-200">
                    <img
                        :src="boardingHouse.imageUrl"
                        class="w-full h-80 object-cover"
                        alt="No Image"
                    />
                    <div class="h-2/3 space-y-1 p-2">
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
        <div class="my-4 flex justify-center">
            <Link
                :href="allBoardingHouse.prev_page_url"
                v-if="allBoardingHouse.prev_page_url"
                class="px-2 py-1 bg-gray-300 rounded mr-2"
            >
                Previous
            </Link>
            <Link
                :href="allBoardingHouse.next_page_url"
                v-if="allBoardingHouse.next_page_url"
                class="px-2 py-1 bg-gray-300 rounded ml-2"
            >
                Next
            </Link>
        </div>
    </section>
    <Footer />
</template>
