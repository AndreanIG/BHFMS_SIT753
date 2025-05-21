<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Footer from "../../Shared/Footer.vue";
import Header from "../../Shared/Header.vue";
import FormErrorMessage from "../../Shared/AccountFormInput/FormErrorMessage.vue";

const props = defineProps({
    boardingHouseId: {
        type: String,
        default: null,
    },
    review: {
        type: Object,
        default: null,
    },
});

const rating = ref(props.review == null ? 0 : props.review.rating);

let form = useForm({
    rating: rating,
    boardingId: props.boardingHouseId,
    description: props.review == null ? "" : props.review.review_desc,
});

let resetStarRating = () => {
    rating.value = 0;
};

let submit = () => {
    form.post("/review/create");
};

let submitUpdate = () => {
    form.post("/review/update");
};

let redirectBack = () => {
    window.history.back();
}
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <form
            @submit.prevent="props.review == null ? submit() : submitUpdate()"
            class="border border-slate-200 space-y-2 px-4 py-6"
        >
            <div>
                <div class="semibold text-2xl text-indigo-700 flex justify-between items-center">
                    <div v-if="props.review == null">CREATE REVIEW</div>
                    <div v-else>VIEW REVIEW</div>
                    <div>
                        <button
                            @click="redirectBack()"
                            class="text-sm bg-red-600 text-white hover:bg-red-800 p-2 flex justify-center items-center"
                        >
                            Back
                        </button>
                    </div>
                </div>
            </div>
            <div class="space-y-2">
                <div>
                    <label class="block text-xl font-semibold"
                        >Your Rating</label
                    >
                    <div class="flex items-center">
                        <div
                            v-for="i in 5"
                            :key="i"
                            class="cursor-pointer mr-2"
                        >
                            <svg
                                class="w-5 h-5 fill-current"
                                :class="{ 'text-yellow-400': i <= rating }"
                                @click="rating = i"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M19.875,7.022c-0.147-0.451-0.564-0.765-1.039-0.765H13.287L10.995,1.03C10.845,0.581,10.47,0.25,10,0.25s-0.845,0.331-0.995,0.78L6.713,6.257H1.164c-0.475,0-0.891,0.314-1.039,0.765c-0.147,0.452-0.014,0.949,0.334,1.245l4.822,4.042l-1.814,8.474c-0.051,0.24,0.024,0.487,0.206,0.656c0.096,0.082,0.209,0.121,0.323,0.121c0.115,0,0.23-0.039,0.327-0.118l5.655-4.743l5.656,4.743c0.094,0.078,0.211,0.118,0.327,0.118c0.113,0,0.225-0.039,0.322-0.121c0.182-0.169,0.258-0.416,0.206-0.656l-1.814-8.474l4.822-4.042C19.889,7.97,20.022,7.474,19.875,7.022z"
                                />
                            </svg>
                        </div>
                        {{ rating }}/5
                    </div>
                    <FormErrorMessage :error-message="form.errors.rating" />
                </div>
                <div>
                    <label class="block text-xl font-semibold"
                        >Review Description</label
                    >
                    <textarea
                        v-model="form.description"
                        class="w-full h-[20vh] p-2.5 border border-gray-300 rounded-lg"
                        placeholder="Please describe your problem"
                    ></textarea>
                    <FormErrorMessage
                        :error-message="form.errors.description"
                    />
                </div>
            </div>
            <div
                class="flex items-baseline justify-betweens space-x-6 mt-auto mb-10 mx-2"
            >
                <button
                    class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                >
                    Submit
                </button>
                <button
                    @click="resetStarRating()"
                    type="reset"
                    class="px-6 py-2 mt-4 text-white bg-red-600 rounded-lg hover:bg-red-900"
                >
                    Reset
                </button>
            </div>
        </form>
    </section>
    <Footer />
</template>
