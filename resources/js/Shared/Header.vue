<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";

import AdminLink from "./HeaderLink/AdminLink.vue";
import OwnerLink from "./HeaderLink/OwnerLink.vue";
import TenantLink from "./HeaderLink/TenantLink.vue";

let hover_profile = ref(false);
let selectedProfile = ref(false);
let selectedLogout = ref(false);

const onPlaceChangedHeader = (addressData, placeResultData) => {
    form.address = placeResultData.formatted_address;
    form.latitude = addressData.latitude;
    form.longitude = addressData.longitude;
};

const form = useForm({
    address: "",
    latitude: 0,
    longitude: 0,
});

const submitHeader = () => {
    form.get("/search");
};
let hoverAdmin1 = ref(false);
let hoverAdmin2 = ref(false);
let hoverAdmin3 = ref(false);
let hoverTenant = ref(false);
let hoverOwner = ref(false);
</script>

<template>
    <section
        class="p-6 bg-indigo-900 flex items-center justify-between text-white"
    >
        <div class="mx-3">
            <Link class="py-2 bg-indigo-900 hover:opacity-25" href="/">
                <img
                    :src="'/storage/BHFMS_transparent.png'"
                    alt="No Image"
                    class="w-16 h-auto"
                />
            </Link>
        </div>
        <div class="">
            <div v-if="$page.props.user" class="flex z-50 space-x-8">
                <AdminLink
                    v-if="$page.props.user.role_id == 1"
                    :hoverAdmin1="hoverAdmin1"
                    :hoverAdmin2="hoverAdmin2"
                    :hoverAdmin3="hoverAdmin3"
                />
                <OwnerLink
                    v-if="
                        $page.props.user.role_id == 3 ||
                        $page.props.user.role_id == 4
                    "
                    :hoverOwner="hoverOwner"
                />
                <TenantLink
                    v-if="$page.props.user.role_id == 2"
                    :hoverTenant="hoverTenant"
                />
            </div>
            <div v-else class="flex z-50 space-x-10">
                <Link
                    class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
                    href="/"
                    >Homepage</Link
                >
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <form
                @submit.prevent="submitHeader"
                class="w-full h-10 flex items-center justify-between border border-gray-500 bg-white rounded-3xl items-center p-3"
            >
                <vue-google-autocomplete
                    id="autocomplete"
                    class="h-full rounded-3xl items-center p-3 text-black"
                    placeholder="Search Here"
                    v-model="address"
                    v-on:placechanged="onPlaceChangedHeader"
                    country="id"
                    types=""
                />
                <div
                    class="flex items-baseline justify-end items-center space-x-10"
                >
                    <button
                        class="text-white w-[3rem] h-[3rem] rounded-full flex items-center justify-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#000000"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>

            <div
                v-if="!$page.props.user"
                class="h-10 w-full items-center text-center z-50"
            >
                <div
                    class="p-2 hover:bg-gray-200 z-50 px-6 py-2 bg-white rounded-2xl text-black z-50"
                >
                    <Link href="/login">Login</Link>
                </div>
            </div>
            <div
                v-else
                @mouseover="hover_profile = true"
                @mouseleave="hover_profile = false"
                class="h-10 w-full items-center text-center z-50 whitespace-nowrap"
            >
                <div
                    v-if="hover_profile == false"
                    class="px-6 py-2 bg-white rounded-2xl text-black z-50 whitespace-nowrap"
                >
                    <div v-if="$page.props.user">
                        {{ $page.props.user.user_name }}
                    </div>
                </div>
                <div
                    v-if="hover_profile == true"
                    class="border-solid border-b-4 border-indigo-900 px-6 py-2 bg-white rounded-2xl text-black z-50 whitespace-nowrap"
                >
                    <div v-if="$page.props.user" class="whitespace-nowrap">
                        {{ $page.props.user.user_name }}
                    </div>
                </div>
                <div
                    class="rounded-lg bg-white text-black z-50"
                    v-if="hover_profile == true"
                >
                    <ul class="z-50">
                        <li class="p-2 hover:bg-gray-200 hover:rounded-lg z-50">
                            <Link href="/profile">Profile</Link>
                        </li>
                        <li
                            v-if="$page.props.user.role_id == 2"
                            class="p-2 hover:bg-gray-200 hover:rounded-lg z-50"
                        >
                            <Link href="/wishlist">Wishlist </Link>
                        </li>
                        <li
                            v-if="$page.props.user.role_id == 2"
                            class="p-2 hover:bg-gray-200 hover:rounded-lg z-50"
                        >
                            <Link href="/review">Review </Link>
                        </li>
                        <li class="p-2 hover:bg-gray-200 hover:rounded-lg z-50">
                            <Link href="/logout">Logout</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
