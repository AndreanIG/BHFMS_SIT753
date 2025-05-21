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
   <div class="flex justify-center w-full py-2 mx-9 mb-1 border border-gray-300 shadow rounded-lg">
        <div class="w-1/4 flex justify-center">
            <!-- Bisa pake css nanti kalo mau -->
            <p v-if="payment.payment_status=='Late'" class="py-2 px-8 bg-red-600 border-2 w-min text-white rounded-md">
                Late
            </p>
            <p v-else-if="payment.payment_status=='Pending'" class="py-2 px-5 bg-gray-600 border-2 w-min text-white rounded-md">
                Pending
            </p>
            <p  v-else-if="payment.payment_status=='Processing'" class="py-2 px-3 bg-yellow-400 border-2 w-min text-white rounded-md">
                Processing
            </p>
            <p v-else-if="payment.payment_status=='Approved'" class="py-2 px-4 bg-green-600 border-2 w-min text-white rounded-md">
                Approved
            </p>
            <p  v-else-if="payment.payment_status=='Rejected'" class="py-2 px-4 bg-yellow-400 border-2 w-min text-white rounded-md">
                Rejected
            </p>
            <p  v-else-if="payment.payment_status=='Canceled'" class="py-2 px-4 bg-yellow-400 border-2 w-min text-white rounded-md">
                Canceled
            </p>
            <p  v-else class="py-2 px-4 bg-yellow-400 border-2 w-min text-white">
                None
            </p>
        </div>
        <div class="w-1/4 m-auto">
            <p>
                {{ convertTime(payment.payment_date) }}
            </p>
        </div>
        <div class="w-1/4 m-auto">
            <p v-if="userRole == 2">
                {{ payment.boarding_name }}
            </p>
            <p v-if="userRole == 3 || userRole == 4">
                {{ payment.user_name }}
            </p>
        </div>

        <div class="w-1/4 ">
            <button class="bg-blue-500 text-white rounded-md px-4 py-2" v-on:click="showInvoiceDetail(payment.invoice_id)">
                Details
            </button>
            <button v-if="(payment.payment_status=='Pending' || payment.payment_status=='Late') && userRole==2"  class="bg-blue-600 text-white rounded-md px-7 py-2 ml-2" @click="redirect('/pay?order='+payment.invoice_id)">
                Pay
            </button>
        </div>
   </div>
</template>
