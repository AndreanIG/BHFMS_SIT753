<script setup>
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import FormTextBoxInput from "../../Shared/AccountFormInput/FormTextBoxInput.vue";
import FormErrorMessage from "../../Shared/AccountFormInput/FormErrorMessage.vue";

let form = useForm({
    email: "",
    password: "",
});

let submit = () => {
    form.post("/login");
};
</script>

<template>
    <Header />
    <div
        class="flex items-center justify-end bg-cover min-h-screen overflow-hidden"
        style="background-image: url('../storage/sofa.png')"
    >
        <div class="px-8 py-6 mt-4 text-left shadow-lg mr-80 my-10 bg-white">
            <h3 class="text-2xl font-bold text-center">
                Login to your account
            </h3>
            <form @submit.prevent="submit">
                <FormErrorMessage :error-message="form.errors.email" />
                <div class="mt-4">
                    <FormTextBoxInput
                        v-model="form.email"
                        :input-type="'email'"
                        :label-name="'Email'"
                    />
                    <div class="mt-4">
                        <FormTextBoxInput
                            v-model="form.password"
                            :input-type="'password'"
                            :label-name="'Password'"
                        />
                    </div>
                    <div class="flex items-baseline justify-between space-x-10">
                        <button
                            class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                        >
                            Login
                        </button>
                        <Link
                            href="/register"
                            class="text-sm text-blue-600 hover:underline"
                            >Don't have an account? Sign Up</Link
                        >
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>
