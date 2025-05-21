<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    currID: String,
    currUser: Object,
    capacity: String,
});

let form = useForm({
    currID: props.currID,
    name: props.currUser.user_name,
    gender: props.currUser.gender,
    email: props.currUser.email,
    dob: props.currUser.date_of_birth,
    phone: props.currUser.phone,
    pic: props.currUser.profile_picture,
    reason: "",
    accept: false,
});

const denyRequest = (this_id) => {
    form.accept = false;
    form.post(`/tenantBoarding/request/${this_id}`, {});
};

const acceptRequest = (this_id) => {
    form.accept = true;
    form.post(`/tenantBoarding/request/${this_id}`, {});
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
                    :href="'/tenantBoarding'"
                >
                    Back
                </Link>
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        Tenant Detail
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="props.capacity"
                            :input-type="'text'"
                            :label-name="'Rooms Left in Boarding House'"
                            :placeholder="'Current Boarding Capacity'"
                        />
                    </div>
                    <hr class="h-px my-8 bg-gray-200 border-0" />
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'User name'"
                            :placeholder="'User name'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.gender"
                            :input-type="'text'"
                            :label-name="'Gender'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.email"
                            :input-type="'text'"
                            :label-name="'Email'"
                            :placeholder="'Email'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.dob"
                            :input-type="'text'"
                            :label-name="'Date of Birth'"
                            :placeholder="'Date of Birth'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.phone"
                            :input-type="'text'"
                            :label-name="'Phone Number'"
                            :placeholder="'phone'"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Decline Reason (Optional)
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
                                class="mt-4 bg-red-700 hover:bg-red-900 text-white font-bold py-4 px-12 rounded"
                                @click.prevent="denyRequest(currID)"
                            >
                                <span>Decline</span>
                            </button>
                        </div>
                        <div class="mt-2 float-right">
                            <button
                                class="mt-4 bg-green-700 hover:bg-green-900 text-white font-bold py-4 px-12 rounded"
                                @click.prevent="acceptRequest(currID)"
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
