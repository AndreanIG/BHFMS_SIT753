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
    currBoarding: Object,
    currFacilities: Object,
    currManager: Object,
    currOwner: Object,
    currImages: Object,
    currType: Object,
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
    owner: props.currOwner.user_name,
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
    reason: "",
    decision: "",
});

const denyRequestBan = (this_id) => {
    form.decision = "ban";
    form.post(`/boardingAdmin/request/${this_id}`, {});
};

const denyRequestDecline = (this_id) => {
    form.decision = "decline";
    form.post(`/boardingAdmin/request/${this_id}`, {});
};

const acceptRequest = (this_id) => {
    form.decision = "accept";
    form.post(`/boardingAdmin/request/${this_id}`, {});
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
                <!--  to  Manager Boarding Page -->
                <Link
                    v-if="$page.props.user.role_id == 4"
                    class="my-2 mx-2 text-m float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded focus:outline-none focus:shadow-outline"
                    :href="'/boardingManager'"
                >
                    Back
                </Link>
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Boarding House Details
                    </h1>
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
                            v-model="form.owner"
                            :input-type="'text'"
                            :label-name="'Boarding House Owner'"
                            :placeholder="'None'"
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

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Accept or Decline Reason (Optional)
                        </label>
                        <textarea
                            v-model="form.reason"
                            class="w-full h-[20vh] p-2.5 border border-gray-300 rounded-lg"
                            placeholder="Fill in your reason for declining or accepting the request"
                        ></textarea>
                    </div>

                    <div class="flow-root">
                        <div class="mt-2 float-left">
                            <button
                                class="mt-4 bg-slate-700 hover:bg-slate-900 text-white font-bold py-4 px-12 rounded"
                                @click.prevent="denyRequestBan(form.currID)"
                            >
                                <span>Decline (Cannot Reapprove)</span>
                            </button>

                            <button
                                class="ml-4 mt-4 bg-red-700 hover:bg-red-900 text-white font-bold py-4 px-12 rounded"
                                @click.prevent="denyRequestDecline(form.currID)"
                            >
                                <span>Decline (Can Reapprove)</span>
                            </button>
                        </div>
                        <div class="mt-2 float-right">
                            <button
                                class="mt-4 bg-green-700 hover:bg-green-900 text-white font-bold py-4 px-12 rounded"
                                @click.prevent="acceptRequest(form.currID)"
                            >
                                <span>Accept</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Footer />
</template>
