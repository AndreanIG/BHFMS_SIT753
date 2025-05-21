<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import FormSelectInputTenant from '../../Shared/Payment/FormSelectInputTenant.vue';
import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import FormTextBoxInputReadOnly from '../../Shared/Payment/FormTextBoxInputReadOnly.vue';
import FormErrorMessage from '../../Shared/AccountFormInput/FormErrorMessage.vue';
import FormSelectInputTransactionType from '../../Shared/Payment/FormSelectInputTransactionType.vue';
import FormTextBoxInputPayment from '../../Shared/Payment/FormTextBoxInputPayment.vue';
import FormCheckboxInput from '../../Shared/Payment/FormCheckboxInput.vue';
import { ref } from 'vue';

const props = defineProps({
    transaction: Array,
    listTenants: Array,
    boardingHouseName: String,
    transactionTypes: Array,
    tenant: Array
});

const convertDateFormat = ($date) => {
    let paymentDate = new Date($date);
    let year = paymentDate.getFullYear();
    let month = String(paymentDate.getMonth() + 1).padStart(2, '0');
    let day = String(paymentDate.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

let form = useForm({
    boardingHouseName: ref(props.boardingHouseName),
    paymentDate: ref(props.transaction != null ? convertDateFormat(props.transaction.payment_date) : ''),
    paymentAmount: ref(props.transaction != null ? props.transaction.amount : 0),
    transactionType: ref(props.transaction != null ? props.transactionTypes[props.transaction.transaction_type_id] : ''),
    tenantEmail: ref(props.transaction != null ? props.tenant.email: ''),
    paymentRepeat: ref(props.transaction != null ? (props.transaction.repeat_payment === 1 ? 'true' : '') : null),
});

let query = new URLSearchParams(window.location.search);

let submit = () => {
    if(props.transaction!=null){
        form.post("/paymentBoarding/edit?order=" + query.get('order'));
    } else{
        form.post("/paymentBoarding/add" + "?boarding=" + query.get('boarding') );
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
                <FormSelectInputTransactionType
                    v-model="form.transactionType"
                    :option-list="transactionTypes"
                    :label-desc="'Transaction Type'"
                    :label-name="'Transaction Type'"
                    :default-text="'Select Transaction Type'"
                    :default-value="transaction != null ? transaction.transaction_type_id : null"
                />
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
                <FormSelectInputTenant
                    v-model="form.tenantEmail"
                    :option-list="listTenants"
                    :label-desc="'Select Tenant'"
                    :label-name="'Tenant'"
                    :default-email="transaction != null ? tenant.email : null"
                    :default-username="transaction != null ? tenant.user_name : null"
                    />
                <FormErrorMessage
                    :error-message="form.errors.tenantEmail"
                />
            </div>
            <div>
                <FormCheckboxInput
                    v-model="form.paymentRepeat"
                        :label-name="'Repeat Payment'"
                        :default-value="transaction != null ? transaction.repeat_payment : null"
                />
                <FormErrorMessage
                    :error-message="form.errors.paymentRepeat"
                />
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