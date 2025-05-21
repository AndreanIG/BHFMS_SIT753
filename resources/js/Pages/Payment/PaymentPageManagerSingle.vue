<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import FormTextBoxInputReadOnly from '../../Shared/Payment/FormTextBoxInputReadOnly.vue';
import FormErrorMessage from '../../Shared/AccountFormInput/FormErrorMessage.vue';
import FormTextBoxInputPayment from '../../Shared/Payment/FormTextBoxInputPayment.vue';
import { ref } from 'vue';

const props = defineProps({
    boardingId: Number,
    boardingHouseName: String,
    transactionType: String,
    tenant: Array,
    transaction: Array,

});

const convertDateFormat = ($date) => {
    let paymentDate = new Date($date);
    let year = paymentDate.getFullYear();
    let month = String(paymentDate.getMonth() + 1).padStart(2, '0');
    let day = String(paymentDate.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

let form = useForm({
    boardingId: ref(props.boardingId != null ? props.boardingId : ''),
    paymentDate: ref(props.transaction != null ? convertDateFormat(props.transaction.payment_date) : ''),
    paymentAmount: ref(props.transaction != null ? props.transaction.amount : 0),
    transactionType: ref(props.transactionType != null ? props.transactionType : ''),
    tenantEmail: ref(props.tenant != null ? props.tenant.email : ''),
});

let query = new URLSearchParams(window.location.search);

let submit = () => {
    if(props.transaction!=null){
        form.post("/downPayment/edit?order=" + query.get('order'));
    } else{
        form.post("/downPayment/add?tenantBoarding=" + query.get('tenantBoarding'));
    }
};

const today = new Date();
today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
const minToday = today.toISOString().split('T')[0];

</script>
<template>
    <Header />
    <div class="m-5 min-h-[75vh]">
        <div class="text-2xl font-semibold">
            Payment Page
        </div>
        <form @submit.prevent="submit">
            <div>
            <FormTextBoxInputReadOnly
                v-model="form.boardingHouseName"
                    :input-type="'text'"
                    :label-name="'Boarding House Name'"
                    :value="boardingHouseName"/>
            </div>
            <div>
                <FormTextBoxInputReadOnly 
                v-model="form.transactionType"
                    :input-type="'text'"
                    :label-name="'Transaction Type'"
                    :value="transactionType"/>
                <FormErrorMessage
                    :error-message="form.errors.transactionType"
                />
            </div>
            <div>
                <FormTextBoxInputPayment
                    v-model="form.paymentDate"
                        :input-type="'date'"
                        :label-name="'Date'"
                        :min-value="minToday"
                />
                <FormErrorMessage
                    :error-message="form.errors.paymentDate"
                />
            </div>
            <div>  
                <label class="block mt-2">Amount</label>
                <input
                    v-model.number="form.paymentAmount"
                    type="number"
                    placeholder="Amount"
                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                />
                <FormErrorMessage
                   :error-message="form.errors.paymentAmount"
                />
            </div>
            <div>
                <FormTextBoxInputReadOnly 
                    :input-type="'text'"
                    :label-name="'Tenant Name'"
                    :value="props.tenant.user_name"/>
            </div>
            <button
                v-if="transaction!=null"
                class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                Edit Payment
            </button>
            <button
                v-else
                class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900">
                Add Payment
            </button>
        </form>
    </div>
    <Footer />
</template>