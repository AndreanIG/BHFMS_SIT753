<script setup>
defineProps({
    userRole: Number,
    invoice: Object
})
const emit = defineEmits(['showDetail']);

let showInvoiceDetail = (invoiceId) => {
    emit('showDetail', invoiceId);
}

let showPopUpDeclined = (invoiceStatus, invoiceId) => {
    emit('showPopUpDeclined', [invoiceStatus, invoiceId]);
}
let showPopUpSuccessful = (invoiceStatus, invoiceId) => {
    emit('showPopUpSuccessful', [invoiceStatus, invoiceId]);
}
const getFileName = (paymentDate, invoiceId) => {
  return new Date(paymentDate).toISOString().slice(0,10).replace(/-/g,"") + invoiceId + '.png';
}

let convertTime = (date) =>{
    return new Date(date).toLocaleDateString()
}

let convertAmount = (amount) => {
    amount = Math.floor(amount / 1000) * 1000;
    return new Number(amount).toLocaleString("id-ID");
}

</script>
<template>
    <div class="w-1/4 flex justify-center">
        <p>
            {{ convertTime(invoice.payment_date) }}
        </p>
    </div>
    <div class="w-1/4 flex justify-center">
        <p>
            {{ convertAmount(invoice.amount) }}
        </p>
    </div>
    <div class="w-1/4 flex justify-center">
        {{ invoice.bank_name }} - {{ invoice.account_number }}
    </div>

    <div class="w-1/4 ">
        <input class="w-1/3 text-center" type="radio" :name="'checkInvoiceStatus'+invoice.invoice_id"  value="successful" v-on:click="showPopUpSuccessful('Successful', invoice.invoice_id)">
        <input class="w-1/3 text-center" type="radio" :name="'checkInvoiceStatus'+invoice.invoice_id" value="declined"  v-on:click="showPopUpDeclined('Declined', invoice.invoice_id)">
        <button class="w-1/3 bg-blue-500 text-white rounded-md px-4 py-2" v-on:click="showInvoiceDetail(invoice.invoice_id)">
            Details
        </button>
      
    </div>
</template>
