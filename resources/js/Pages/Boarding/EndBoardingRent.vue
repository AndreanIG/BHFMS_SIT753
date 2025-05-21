<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";

const prop = defineProps({
    tenantBoarding: Object,
    minEndDate: String,
});

const form = useForm({
    start_date: prop.tenantBoarding.start_date,
    end_date: "",
});

const submit = () => {
    form.post(`/boarding/rent/end/${prop.tenantBoarding.id}`, {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>
<template>
    <Header />
    <div class="overflow-x-auto">
        <div
            class="min-w-screen min-h-screen bg-gray-100 flex items-center justify-center bg-gray font-sans overflow-hidden"
        >
            <div class="w-11/12 mt-5">
                <Link
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingTenant'"
                >
                    Back
                </Link>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        End Boarding Rent
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.start_date"
                            :input-type="'date'"
                            :label-name="'Start Date'"
                            :placeholder="'Start Date'"
                            :error-message="form.errors.start_date"
                            :read-only="true"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            End Date (At Least 1 week after today or minimum 1
                            month after start date of rent)
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            v-model="form.end_date"
                            type="date"
                            :min="prop.minEndDate"
                            placeholder="'End Date'"
                        />

                        <div
                            v-if="form.errors.end_date"
                            v-text="form.errors.end_date"
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
