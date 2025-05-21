<script setup>
import { Inertia } from "@inertiajs/inertia";
defineProps({
    userRole: Number,
    payment: Object
})
const emit = defineEmits(['showDetail']);

let showInvoiceDetail = (invoice_id) => {
    emit('showDetail',invoice_id);
}

let redirect = (url) => {
    Inertia.get(url);
}

let convertTime = (date) =>{
    return new Date(date).toLocaleDateString()
}
</script>
<template>
    <div class="w-1/4 flex justify-center">
        <p v-if="payment.payment_transferred_status=='Processing_Refund'" class="py-2 px-8 bg-yellow-400 border-2 w-min text-white rounded-md">
            Processing_Refund
        </p>
        <p v-else-if="payment.payment_transferred_status=='Declined'" class="py-2 px-5 bg-yellow-400 border-2 w-min text-white rounded-md">
            Declined
        </p>
    </div>
    <div class="w-1/4 m-auto">
        <p>
            {{ convertTime(payment.payment_date) }}
        </p>
    </div>
    <div class="w-1/4 m-auto">
        <p>
            {{ payment.boarding_name }}
        </p>
    </div>

    <div class="w-1/4 ">
        <button class="bg-blue-500 text-white rounded-md px-4 py-2" v-on:click="showInvoiceDetail(payment.invoice_id)">
            Details
        </button>
    </div>
</template>
