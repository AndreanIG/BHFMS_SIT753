<script setup>
import Footer from "../../Shared/Footer.vue";
import Header from "../../Shared/Header.vue";
import FormErrorMessage from "../../Shared/AccountFormInput/FormErrorMessage.vue";
import FormSelectInput from "../../Shared/AccountFormInput/FormSelectInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";

defineProps({
    allComplainType: Object,
})

let form = useForm({
    complainType: "",
    description: "",
    pictureFile: "",
});

let submit = () => {
    form.post("/complain/create");
}
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <form @submit.prevent="submit" class="border border-slate-200 space-y-2 px-4 py-6">
            <div class="semibold text-2xl text-indigo-700">CREATE COMPLAIN</div>
            <div>
                <div>
                    <FormSelectInput
                        v-model="form.complainType"
                        :label-name="'Complain Type'"
                        :option-list="allComplainType"
                    />
                    <FormErrorMessage :error-message="form.errors.complainType" />
                </div>
            </div>
            <div>
                <label class="block">Complain Description</label>
                <textarea
                    v-model="form.description"
                    class="w-full h-[20vh] p-2.5 border border-gray-300 rounded-lg"
                    placeholder="Please describe your problem"
                ></textarea>
            </div>
            <div>
                <label class="block">Upload Photo/Files</label>
                <input
                    type="file"
                    @input="form.pictureFile = $event.target.files[0]"
                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                />
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
