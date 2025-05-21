<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TextBoxInput from "../../Shared/BoardingShared/TextBoxInput.vue";

const userProp = defineProps({
    currUser: Object,
    currRole: String,
    images: Array,
});

const images = ref([]);
const previewImage = ref([]);

const form = useForm({
    name: userProp.currUser.user_name,
    gender: String(userProp.currUser.gender),
    status: userProp.currUser.user_status,
    user_role: userProp.currRole,
    email: userProp.currUser.email,
    dob: userProp.currUser.date_of_birth,
    phone: String(userProp.currUser.phone),
    images: images,
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
                    :href="'/userAll'"
                >
                    Back
                </Link>
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h1 class="text-blue-600 font-bold text-2xl mb-8">
                        User Detail
                    </h1>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.name"
                            :input-type="'text'"
                            :label-name="'User Name'"
                            :placeholder="'User Name'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.gender"
                            :input-type="'text'"
                            :label-name="'Gender'"
                            :placeholder="'Gender'"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.user_role"
                            :input-type="'text'"
                            :label-name="'User Role'"
                            :placeholder="''"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.phone"
                            :input-type="'text'"
                            :label-name="'Phone number'"
                            :placeholder="''"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.status"
                            :input-type="'text'"
                            :label-name="'Status'"
                            :placeholder="''"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.email"
                            :input-type="'text'"
                            :label-name="'Email'"
                            :placeholder="''"
                        />
                    </div>
                    <div class="mb-4">
                        <TextBoxInput
                            :read-only="true"
                            v-model="form.dob"
                            :input-type="'text'"
                            :label-name="'Date of Birth'"
                            :placeholder="''"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="images"
                        >
                            Current Image
                        </label>

                        <div class="items-center">
                            <img
                                class="w-40 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                :src="userProp.currUser.profile_picture"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Footer />
</template>
