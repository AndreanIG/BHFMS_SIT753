<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    reason: String,
    tenantBoarding: Object,
    currUser: Object,
    capacity: String,
});

let form = useForm({
    currID: props.currUser.id,
    name: props.currUser.user_name,
    gender: props.currUser.gender,
    email: props.currUser.email,
    dob: props.currUser.date_of_birth,
    phone: props.currUser.phone,
    pic: props.currUser.profile_picture,
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
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="props.reason"
                            :input-type="'textarea'"
                            :label-name="'Declined / Accepted Reason'"
                            :placeholder="'Reasons'"
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
                                v-model="props.tenantBoarding.start_date"
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
                                v-model="props.tenantBoarding.end_date"
                            />
                        </div>
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
                </form>
            </div>
        </div>
    </div>
    <Footer />
</template>
