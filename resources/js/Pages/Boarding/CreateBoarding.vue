<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import VueMultiselect from "vue-multiselect";
import Multiselect from "vue-multiselect";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";

defineProps({
    types: Object,
    facilities: Object,
    managers: Object,
    locations: Object,
    images: Array,
});
const selectedType = ref("");
const selectedFacility = ref("");
const selectedManager = ref("");
const address = ref("");
const images = ref([]);
const previewImage = ref([]);

const getAddressData = (addressData, placeResultData) => {
    address.value = placeResultData.formatted_address;
    form.lat = addressData.latitude;
    form.lng = addressData.longitude;
};

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

const customLabelManager = ({ user_name, email }) =>
    `${user_name} - (${email})`;

const form = useForm({
    name: "",
    address: address,
    type: selectedType,
    facility: selectedFacility,
    rooms: "",
    price: "",
    description: "",
    images: images,
    sharedBathroom: false,
    manager: selectedManager,
    lat: "",
    lng: "",
});

const submit = () => {
    form.post("/boarding/create", {
        preserveScroll: true,
        preserveState: true,
        // onSuccess: () => form.reset("password"),
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
                    v-if="$page.props.user.role_id == 1"
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingAdmin'"
                >
                    Back
                </Link>
                <!--  to Owner Boarding Page -->
                <Link
                    v-if="$page.props.user.role_id == 3"
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingOwner'"
                >
                    Back
                </Link>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Add New Boarding House
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'Boarding House Name'"
                            :placeholder="'Boarding House Name'"
                            :error-message="form.errors.name"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="address"
                        >
                            Address
                        </label>
                        <vue-google-autocomplete
                            id="address"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            classname="form-control"
                            placeholder="Select Address"
                            v-on:placechanged="getAddressData"
                            country="id"
                            types=""
                            v-model="form.address"
                        >
                        </vue-google-autocomplete>
                        <div
                            v-if="form.errors.address"
                            v-text="form.errors.address"
                            class="text-red-500 text-xs mt-1"
                        />
                    </div>
                    <div class="mb-4 flow-root">
                        <div class="float-left w-5/12">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                for="lat"
                            >
                                Latitude
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="lat"
                                type="text"
                                placeholder="Latitude"
                                readonly
                                v-model="form.lat"
                            />
                        </div>
                        <div class="float-right w-5/12">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                for="lng"
                            >
                                Longitude
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="lng"
                                type="text"
                                placeholder="Longitude"
                                readonly
                                v-model="form.lng"
                            />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="type_id"
                        >
                            Boarding House Type
                        </label>
                        <VueMultiselect
                            v-model="selectedType"
                            :options="types.map((type) => type.id)"
                            :custom-label="
                                (opt) =>
                                    types.find((x) => x.id == opt)
                                        .boarding_type_name
                            "
                        >
                        </VueMultiselect>
                        <div
                            v-if="form.errors.type"
                            v-text="form.errors.type"
                            class="text-red-500 text-xs mt-1"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="facilities_id[]"
                        >
                            Facilites
                        </label>
                        <multiselect
                            v-model="selectedFacility"
                            :options="facilities.map((facility) => facility.id)"
                            :custom-label="
                                (opt) =>
                                    facilities.find((x) => x.id == opt)
                                        .facility_detail_name
                            "
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Select Facilities"
                        >
                        </multiselect>
                        <div
                            v-if="form.errors.facility"
                            v-text="form.errors.facility"
                            class="text-red-500 text-xs mt-1"
                        />
                    </div>
                    <div class="mb-4 flow-root">
                        <div class="float-left">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                for="sharedBathroom"
                            >
                                Shared Bathroom ?
                            </label>
                        </div>

                        <div class="flex float-right">
                            <input
                                v-model="form.sharedBathroom"
                                class="mb-2"
                                type="checkbox"
                                value=""
                                id="sharedBathroom"
                            />
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2 ml-2"
                                for="sharedBathroom"
                            >
                                Check if True
                            </label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.rooms"
                            :input-type="'text'"
                            :label-name="'Number of Rooms'"
                            :placeholder="'Number of Rooms'"
                            :error-message="form.errors.rooms"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.price"
                            :input-type="'text'"
                            :label-name="'Price per Month'"
                            :placeholder="'Price per Month'"
                            :error-message="form.errors.price"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            v-model="form.description"
                            :input-type="'text'"
                            :label-name="'Description'"
                            :placeholder="'Description'"
                            :error-message="form.errors.description"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="manager"
                        >
                            Select Manager (Optional)
                        </label>
                        <VueMultiselect
                            v-model="selectedManager"
                            :options="managers"
                            :custom-label="customLabelManager"
                            track-by="user_name"
                        >
                        </VueMultiselect>
                        <div
                            v-if="form.errors.manager"
                            v-text="form.errors.manager"
                            class="text-red-500 text-xs mt-1"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="images"
                        >
                            Pictures
                        </label>
                        <input
                            id="images"
                            type="file"
                            multiple
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
