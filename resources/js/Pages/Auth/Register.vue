<script setup>
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import FormErrorMessage from "../../Shared/AccountFormInput/FormErrorMessage.vue";
import FormTextBoxInput from "../../Shared/AccountFormInput/FormTextBoxInput.vue";
import FormSelectInput from "../../Shared/AccountFormInput/FormSelectInput.vue";

let form = useForm({
    firstName: "",
    lastName: "",
    gender: "",
    dateOfBirth: "",
    phoneNumber: "",
    email: "",
    password: "",
    confirmPassword: "",
    userRole: "",
    bankName: "",
    accountNumber: "",
});

let submit = () => {
    form.post("/register");
};

let genderList = ["Male", "Female"];
let roleList = ["Tenant", "Owner", "Manager"];
</script>

<template>
    <Header />
    <div
        class="flex items-center justify-center bg-cover min-h-screen overflow-hidden"
        style="background-image: url('../storage/sofa.png')"
    >
        <div class="px-8 py-6 mt-4 text-left shadow-lg my-10 bg-white">
            <h3 class="text-2xl font-bold text-center">Welcome to BHFMS</h3>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <div class="flex flex-column">
                        <div class="mx-2">
                            <FormTextBoxInput
                                v-model="form.firstName"
                                :input-type="'text'"
                                :label-name="'First Name'"
                            />
                            <FormErrorMessage
                                :error-message="form.errors.firstName"
                            />
                        </div>
                        <div class="mx-2">
                            <FormTextBoxInput
                                v-model="form.lastName"
                                :input-type="'text'"
                                :label-name="'Last Name'"
                            />
                            <FormErrorMessage
                                :error-message="form.errors.lastName"
                            />
                        </div>
                    </div>
                    <div class="mt-4 mx-2">
                        <FormSelectInput
                            v-model="form.gender"
                            :label-name="'Gender'"
                            :option-list="genderList"
                            :default-text="'Select your Gender'"
                        />
                        <FormErrorMessage :error-message="form.errors.gender" />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormTextBoxInput
                            v-model="form.dateOfBirth"
                            :input-type="'date'"
                            :label-name="'Date of Birth'"
                        />
                        <FormErrorMessage
                            :error-message="form.errors.dateOfBirth"
                        />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormTextBoxInput
                            v-model="form.phoneNumber"
                            :input-type="'text'"
                            :label-name="'Phone Number'"
                        />
                        <FormErrorMessage
                            :error-message="form.errors.phoneNumber"
                        />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormTextBoxInput
                            v-model="form.email"
                            :input-type="'email'"
                            :label-name="'Email'"
                        />
                        <FormErrorMessage :error-message="form.errors.email" />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormTextBoxInput
                            v-model="form.password"
                            :input-type="'password'"
                            :label-name="'Password'"
                        />
                        <FormErrorMessage
                            :error-message="form.errors.password"
                        />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormTextBoxInput
                            v-model="form.confirmPassword"
                            :input-type="'password'"
                            :label-name="'Confirm Password'"
                        />
                        <FormErrorMessage
                            :error-message="form.errors.confirmPassword"
                        />
                    </div>
                    <div class="mt-4 mx-2">
                        <FormSelectInput
                            v-model="form.userRole"
                            :label-name="'Role'"
                            :option-list="roleList"
                            :default-text="'Role'"
                        />
                        <FormErrorMessage
                            :error-message="form.errors.userRole"
                        />
                    </div>
                    <div v-if="form.userRole == 'Owner'">
                        <div class="mt-4 mx-2">
                            <FormTextBoxInput
                                v-model="form.bankName"
                                :input-type="'text'"
                                :label-name="'Bank Name'"
                            />
                            <FormErrorMessage
                                :error-message="form.errors.bankName"
                            />
                        </div>
                        <div class="mt-4 mx-2">
                            <FormTextBoxInput
                                v-model="form.accountNumber"
                                :input-type="'text'"
                                :label-name="'Account Number'"
                            />
                            <FormErrorMessage
                                :error-message="form.errors.accountNumber"
                            />
                        </div>
                    </div>
                    <div
                        class="flex items-baseline justify-betweens space-x-6 mt-4"
                    >
                        <button
                            class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                        >
                            Register
                        </button>
                        <Link
                            href="/login"
                            class="text-xs text-blue-600 hover:underline"
                        >
                            Already Have an Account? Login
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>
