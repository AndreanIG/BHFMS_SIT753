<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";
import VueMultiselect from "vue-multiselect";
const form = useForm({
    name: "",
    status: "",
});

const status_options = ["available", "disable"];

const submit = () => {
    form.post("/paymentMethod/create", {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>
<template>
    <Header />
    <div class="overflow-x-auto">
        <div
            class="min-w-screen min-h-screen bg-gray-100 flex justify-center bg-gray font-sans overflow-hidden"
        >
            <div class="w-11/12 mt-5">
                <!-- to Admin Boarding Page -->
                <Link
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/paymentMethodAll'"
                >
                    Back
                </Link>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Add new Payment Method
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'Payment Method Name'"
                            :placeholder="'Payment Method Name'"
                            :error-message="form.errors.name"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="status"
                        >
                            Status
                        </label>
                        <VueMultiselect
                            v-model="form.status"
                            :options="status_options"
                            :close-on-select="true"
                        >
                        </VueMultiselect>
                        <div
                            v-if="form.errors.status"
                            v-text="form.errors.status"
                            class="text-red-500 text-xs mt-1"
                        />
                    </div>

                    <div class="flex justify-center">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Footer />
</template>
