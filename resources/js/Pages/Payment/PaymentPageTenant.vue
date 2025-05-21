<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import FormTextBoxInputReadOnly from '../../Shared/Payment/FormTextBoxInputReadOnly.vue';
import FormErrorMessage from '../../Shared/AccountFormInput/FormErrorMessage.vue';
import FormSelectInputPaymentMethod from '../../Shared/Payment/FormSelectInputPaymentMethod.vue';

defineProps({
    listPaymentMethod: Array,
    paymentDetail: Object
});

let form = useForm({
    paymentMethod: "",
    proofOfPayment: "",
});

let query = new URLSearchParams(window.location.search);

let submit = () => {
    form.post("/pay?order="+query.get('order'));
};

const updateValue = (value) => {
    form.paymentMethod = value
}
let convertAmount = (amount) => {
    return new Number(amount).toLocaleString("id-ID");
}
</script>
<template>
    <Header />
    <div class="m-5 min-h-[75vh]">
        <div class="text-2xl font-semibold mb-3">
            Make Payment
        </div>
        <form @submit.prevent="submit">
            <div class="w-[450px]">
                <FormTextBoxInputReadOnly
                    :input-type="'text'"
                    :label-name="'Boarding House Name'"
                    :value="paymentDetail.boarding_name"
                    />
            </div>
            <div class="w-[450px] my-2">
                <FormTextBoxInputReadOnly
                        :input-type="'date'"
                        :label-name="'Month'"
                        :value="paymentDetail.payment_date"
                        />
            </div>
            <div class="w-[450px] my-2">
                <FormSelectInputPaymentMethod
                    v-model="form.paymentMethod"
                    :option-list="listPaymentMethod"
                    :label-desc="'Payment Method'"
                    :label-name="'Payment Method'"
                    :default-text="'Select Payment Method'"
                    @update:value="updateValue" 
                />
                <FormErrorMessage
                    :error-message="form.errors.paymentMethod"
                />
            </div>
            <div class="w-[450px] my-2">
                <FormTextBoxInputReadOnly
                        :input-type="'text'"
                        :label-name="'Amount'"
                        :value="convertAmount(paymentDetail.amount)"/>
                <FormErrorMessage
                   :error-message="form.errors.paymentAmount"
                />
            </div>
            <div class="w-[450px] my-2">
                <label class="block">Upload Proof of Payment</label>
                <input
                    type="file"
                    @input="form.proofOfPayment = $event.target.files[0]"
                    accept="image/x-png,image/jpeg"
                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                />
            </div>
            <FormErrorMessage
                   :error-message="form.errors.proofOfPayment"
                />
            <button
                class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                Make Payment
            </button>
        </form>
    </div>
    <Footer />
</template>