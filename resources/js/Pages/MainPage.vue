<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Footer from "../Shared/Footer.vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import Header from "../Shared/Header.vue";
import { ref } from "vue";

const props = defineProps({
    highlyRatedBoardingHouse: Array,
});

const address = ref("");

const onPlaceChanged = (addressData, placeResultData) => {
    form.address = placeResultData.formatted_address;
    form.latitude = addressData.latitude;
    form.longitude = addressData.longitude;
};

const form = useForm({
    address: "",
    latitude: 0,
    longitude: 0,
});

const submit = () => {
    form.get("/search");
};
</script>

<template>
    <Header />
    <section>
        <div
            class="w-full h-[80vh] flex items-center justify-start bg-cover"
            style="background-image: url('../storage/sofa.png')"
        >
            <div
                class="w-[45vh] px-8 py-6 mt-4 text-left ml-80 border-gray-500 rounded bg-white bg-opacity-60 rounded-3xl"
            >
                <form @submit.prevent="submit">
                    <div class="mt-4 space-y-3">
                        <p class="text-3xl text-gray-900 font-semibold">
                            Have a location in mind?
                        </p>
                        <p class="text-lg text-gray-900 font-semibold">
                            Find your ideal place to stay
                        </p>
                        <div
                            class="flex w-full h-[3rem] justify-between border border-gray-500 bg-white rounded-3xl items-center p-3"
                        >
                            <vue-google-autocomplete
                                id="autocomplete"
                                class="w-full h-[2rem] rounded-3xl items-center p-3"
                                placeholder="Search Here"
                                v-model="address"
                                v-on:placechanged="onPlaceChanged"
                            />
                            <div
                                class="flex items-baseline justify-end items-center space-x-10"
                            >
                                <button
                                    class="text-white w-[3rem] h-[3rem] rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="28"
                                        height="28"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#000000"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line
                                            x1="21"
                                            y1="21"
                                            x2="16.65"
                                            y2="16.65"
                                        ></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mx-16 my-2 space-y-2">
            <!-- see all accomodation -->
            <p class="text-2xl font-semibold">See All Accomodation</p>
            <p class="text-md text-gray-600">
                Look at all of our registered accomodation
            </p>
            <div
                class="w-full h-[50vh] flex justify-center items-end"
                style="background-image: url('../storage/sofa.png')"
            >
                <Link
                    href="/boarding/all"
                    class="w-60 h-14 mb-32 bg-white flex items-center justify-center rounded-md"
                >
                    Discover Accomodation
                    <div class="ml-3 text-lg">></div>
                </Link>
            </div>
        </div>
        <div class="mx-16 my-2 space-y-2">
            <!-- Highly rated experience -->
            <p class="text-2xl font-semibold">Highly Rated Experiences</p>
            <p class="text-md text-gray-600">Our highly rated accomodation</p>
            <div class="w-full min-h-[50vh] flex">
                <div
                    v-for="(
                        boardingHouse, key
                    ) in props.highlyRatedBoardingHouse"
                    :key="key"
                    class="w-1/6 my-2 mr-2 bg-white shadow-xl"
                >
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
    </section>
    <Footer />
</template>
