<script setup>
import Header from "../Shared/Header.vue";
import Footer from "../Shared/Footer.vue";
import FormTextBoxInput from "../Shared/AccountFormInput/FormTextBoxInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import FormErrorMessage from "../Shared/AccountFormInput/FormErrorMessage.vue";

const props = defineProps({
    userDetails: Object,
});

let form = useForm({
    name: props.userDetails.user_name,
    email: props.userDetails.email,
    dateOfBirth: props.userDetails.date_of_birth,
    phoneNumber: props.userDetails.phone,
    profilePicture: props.userDetails.profile_picture,
});

let submit = () => {
    form.post("/profile/update");
};
</script>

<template>
    <Header />
    <section
        class="w-full min-h-[88vh] flex justify-center bg-cover"
        style="background-image: url('../storage/sofa.png')"
    >
        <div
            class="flex my-5 ml-10 mr-5 bg-white w-1/6 h-2/5 justify-center items-center"
        >
            <img
                :src="
                    userDetails.profile_picture
                        ? userDetails.profile_picture
                        : '../storage/images/CJ-GTASA.png'
                "
                alt="No Image"
                class="h-full w-full"
            />
        </div>
        <div class="flex flex-col my-5 ml-5 mr-10 bg-white w-1/2 h-2/3">
            <div class="text-2xl mt-4 mx-4 text-indigo-700">
                Profile Account
            </div>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="flex flex-col grow m-4 space-y-2">
                    <FormTextBoxInput
                        v-model="form.name"
                        :input-type="'text'"
                        :label-name="'Name'"
                    />
                    <FormErrorMessage :error-message="form.errors.name" />
                    <FormTextBoxInput
                        v-model="form.email"
                        :input-type="'email'"
                        :label-name="'Email'"
                    />
                    <FormErrorMessage :error-message="form.errors.email" />
                    <FormTextBoxInput
                        v-model="form.dateOfBirth"
                        :input-type="'date'"
                        :label-name="'Date of Birth'"
                    />
                    <FormErrorMessage
                        :error-message="form.errors.dateOfBirth"
                    />

                    <FormTextBoxInput
                        v-model="form.phoneNumber"
                        :input-type="'text'"
                        :label-name="'Phone Number'"
                    />
                    <FormErrorMessage
                        :error-message="form.errors.phoneNumber"
                    />
                    <label class="block">Profile Picture Image</label>
                    <input
                        type="file"
                        @input="form.profilePicture = $event.target.files[0]"
                        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                    />
                    <FormErrorMessage
                        :error-message="form.errors.profilePicture"
                    />
                    <div
                        class="flex items-baseline justify-betweens space-x-6 mt-auto mb-10 mx-2"
                    >
                        <button
                            class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                        >
                            Update
                        </button>
                        <button
                            type="reset"
                            class="px-6 py-2 mt-4 text-white bg-red-600 rounded-lg hover:bg-red-900"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <Footer />
</template>
