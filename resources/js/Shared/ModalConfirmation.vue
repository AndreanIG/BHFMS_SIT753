<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, reactive } from "vue";
defineProps({
    currID: Number,
    boarding: Object,
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
};
</script>
<template>
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
</template>
