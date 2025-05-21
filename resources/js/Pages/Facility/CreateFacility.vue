<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";

defineProps({
    facility: Object,
    images: Array,
});

const images = ref([]);
const previewImage = ref([]);

const onFileChange = (e) => {
    const selectedFiles = e.target.files;
    for (let i = 0; i < selectedFiles.length; i++) {
        console.log(selectedFiles[i]);
        images.value.push(selectedFiles[i]);
    }

    for (let i = 0; i < images.value.length; i++) {
        let reader = new FileReader();
        reader.readAsDataURL(images.value[i]);
        reader.onload = (e) => {
            // images.value[i].src = reader.result;
            previewImage.value[i] = e.target.result;
            // images.value[i].src = e.target.result;
        };
    }
};

const deleteFileUploaded = (idx) => {
    previewImage.value.splice(idx, 1);
    images.value.splice(idx, 1);
};

const form = useForm({
    name: "",
    images: images,
});

const submit = () => {
    form.post("/facility/create", {
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
                <!-- to Admin Boarding Page -->
                <Link
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/facilityAll'"
                >
                    Back
                </Link>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Add new Facility
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'Facility Name'"
                            :placeholder="'Facility Name'"
                            :error-message="form.errors.name"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="images"
                        >
                            Image
                        </label>
                        <input
                            id="images"
                            type="file"
                            @change="onFileChange"
                            class="mb-2 mt-2"
                        />
                        <div
                            v-if="form.errors.images"
                            v-text="form.errors.images"
                            class="text-red-500 text-xs mt-1"
                        />

                        <div
                            v-for="(image, key) in images"
                            :key="key"
                            class="flow-root mt-4 items-center align-center flex shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <div class="float-left flex items-center">
                                <img
                                    class="w-40 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    :src="previewImage[key]"
                                />
                                <div class="mt-4 ml-2">
                                    {{ image.name }}
                                </div>
                            </div>
                            <div
                                class="float-right align-middle items-center flex"
                            >
                                <button
                                    class="mt-4 bg-red-700 hover:bg-red-900 text-white font-bold py-2 px-4 rounded"
                                    @click.prevent="deleteFileUploaded(key)"
                                >
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
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
