<script setup>
import Header from "../Shared/Header.vue";
import Footer from "../Shared/Footer.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { reactive, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

defineProps({
    wishlistedBoardingHouses: Object,
});

let mouseover = ref(false);
let popup = reactive({
    show: false,
    boarding_id: 0,
    boarding_name: "",
});

let strokeColor = () => {
    return mouseover.value == false ? "#d0021b" : "#b91c1c";
};

let removeFromWishlist = (id) => {
    Inertia.post(
        "/wishlist/remove",
        {
            id: id,
        },
        { preserveScroll: true }
    );
};

let enableDisablePopup = (id, name) => {
    popup.show = !popup.show;
    popup.boarding_id = id;
    popup.boarding_name = name;
}

</script>

<template>
    <Header />
    <section class="mx-2 my-2 space-y-2 min-h-[60vh]">
        <div class="text-2xl font-semibold">All Wishlisted Boarding House</div>
        <div class="text-md text-gray-600">
            List of your wishlisted boarding house
        </div>
        <div class="flex flex-wrap">
            <div
                v-for="boardingHouse in wishlistedBoardingHouses"
                class="w-1/6 h-1/3 mx-1 my-2 pb-1 shadow-lg"
            >
                <div class="relative">
                    <div
                        class="flex justify-end hover:cursor-pointer absolute top-0 right-0 m-1"
                        @mouseover="mouseover = true"
                        @mouseleave="mouseover = false"
                        @click="enableDisablePopup(boardingHouse.id, boardingHouse.boarding_name)"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            :stroke="strokeColor()"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                            ></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </div>
                    <Link
                        href="/boarding/detail"
                        :data="{ id: boardingHouse.id }"
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
                            <div class="text-xs text-gray-600">
                                {{ boardingHouse.address }}
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </section>
    <div
        v-if="popup.show"
        class="fixed inset-0 grid place-items-center"
        style="background: rgba(0, 0, 0, 0.4)"
        @click="popup.show = false"
    >
        <div class="bg-white w-5/12 rounded">
            <header class="text-xl m-3">Confirmation</header>
            <div class="mx-3">
                Remove {{ popup.boarding_name }} from wishlist ?
            </div>
            <button
                class="bg-blue-500 hover:bg-blue-600 rounded p-2 ml-3 my-3 text-white"
                type="button"
                @click="removeFromWishlist(popup.boarding_id)"
            >
                Confirm
            </button>
            <button
                class="bg-red-500 hover:bg-red-600 rounded p-2 m-3 text-white"
                type="button"
                @click="popup.show = false"
            >
                Back
            </button>
        </div>
    </div>

    <Footer />
</template>
