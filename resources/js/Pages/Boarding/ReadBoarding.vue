<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import VueMultiselect from "vue-multiselect";
import Multiselect from "vue-multiselect";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    currTenant: Object,
    currBoarding: Object,
    currFacilities: Object,
    currManager: Object,
    currImages: Object,
    currType: Object,
    currVacancy: String,
    currOwner: Object,
    images: Array,
});

const selectedFacility = ref(props.currFacilities);
const allFacility = ref(props.currFacilities);
const selectedManager = props.currManager
    ? props.currManager.user_name + " (" + props.currManager.email + ")"
    : "None";
const images = ref([]);

let form = useForm({
    currID: props.currBoarding.id,
    name: props.currBoarding.boarding_name,
    address: props.currBoarding.address,
    facility: selectedFacility,
    rooms: String(props.currBoarding.rooms),
    price: String(props.currBoarding.price),
    description: props.currBoarding.boarding_desc,
    images: images,
    sharedBathroom: props.currBoarding.sharedBathroom,
    manager: selectedManager,
    lat: props.currBoarding.latitude,
    lng: props.currBoarding.longitude,
});
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
                <!--  to  Manager Boarding Page -->
                <Link
                    v-if="$page.props.user.role_id == 4"
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingManager'"
                >
                    Back
                </Link>

                <!--  to  Tenant Boarding Page -->
                <Link
                    v-if="$page.props.user.role_id == 2"
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingTenant'"
                >
                    Back
                </Link>
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Boarding House Details
                    </h1>
                    <div
                        v-if="
                            $page.props.user.role_id == 3 ||
                            $page.props.user.role_id == 4
                        "
                    >
                        <div class="mb-4">
                            <TextBoxInput
                                :read-only="true"
                                v-model="props.currOwner.declined_reason"
                                :input-type="'textarea'"
                                :label-name="'Declined / Accepted Reason'"
                                :placeholder="'None'"
                            />
                        </div>
                        <div class="mb-4">
                            <TextBoxInput
                                :read-only="true"
                                v-model="props.currVacancy"
                                :input-type="'text'"
                                :label-name="'Current Available Vacancy'"
                                :placeholder="'Vacancy'"
                            />
                        </div>
                        <hr class="h-px my-8 bg-gray-200 border-0" />
                    </div>

                    <div v-if="$page.props.user.role_id == 2">
                        <div class="mb-4">
                            <TextBoxInput
                                :read-only="true"
                                v-model="props.currTenant.declined_reason"
                                :input-type="'textarea'"
                                :label-name="'Declined / Accepted Reason'"
                                :placeholder="'None'"
                            />
                        </div>

                        <div class="mb-4 flow-root">
                            <div class="float-left w-5/12">
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-2"
                                    for="start"
                                >
                                    Start Rent Date
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="lat"
                                    type="text"
                                    placeholder="start"
                                    readonly
                                    v-model="props.currTenant.start_date"
                                />
                            </div>
                            <div class="float-right w-5/12">
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-2"
                                    for="end"
                                >
                                    End Rent Date
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="lng"
                                    type="text"
                                    placeholder="End Date not yet determined"
                                    readonly
                                    v-model="props.currTenant.end_date"
                                />
                            </div>
                        </div>
                        <hr class="h-px my-8 bg-gray-200 border-0" />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'Boarding House Name'"
                            :placeholder="'Boarding House Name'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.address"
                            :input-type="'text'"
                            :label-name="'Address'"
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
                        <TextBoxInput
                            :read-only="true"
                            v-model="props.currType.boarding_type_name"
                            :input-type="'text'"
                            :label-name="'Boarding House Type'"
                            :placeholder="'Boarding House Type'"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="facilities_id"
                        >
                            Facilites
                        </label>
                        <multiselect
                            v-if="props.currFacilities"
                            v-model="selectedFacility"
                            :options="allFacility"
                            label="facility_detail_name"
                            track-by="facility_detail_name"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Select Facilities"
                            :disabled="true"
                        >
                        </multiselect>
                        <input
                            v-else
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="text"
                            placeholder="No Facilities"
                            readonly
                        />
                    </div>
                    <div class="mb-4">
                        <div class="">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2"
                                for="sharedBathroom"
                            >
                                Shared Bathroom ?
                            </label>
                        </div>

                        <div
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <h1
                                v-if="
                                    props.currBoarding.shared_bathroom == true
                                "
                            >
                                Yes
                            </h1>
                            <h1 v-else>No</h1>
                        </div>
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.rooms"
                            :input-type="'text'"
                            :label-name="'Number of Rooms'"
                            :placeholder="'Number of Rooms'"
                            :error-message="form.errors.rooms"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.price"
                            :input-type="'text'"
                            :label-name="'Price per Month'"
                            :placeholder="'Price per Month'"
                            :error-message="form.errors.price"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.description"
                            :input-type="'text'"
                            :label-name="'Description'"
                            :placeholder="'Description'"
                            :error-message="form.errors.description"
                        />
                    </div>

                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.manager"
                            :input-type="'text'"
                            :label-name="'Boarding House Manager'"
                            :placeholder="'None'"
                        />
                    </div>

                    <!-- Preview Image in Database -->
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="images"
                        >
                            Current Images
                        </label>
                        <div
                            class="flow-root mt-4 items-center align-center flex shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            v-for="img in currImages"
                        >
                            <div class="float-left flex items-center">
                                <img
                                    class="w-40 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    :src="`${img.image}`"
                                />
                                <div class="mt-4 ml-2">
                                    {{ img.image.split("/")[3] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Footer />
</template>
