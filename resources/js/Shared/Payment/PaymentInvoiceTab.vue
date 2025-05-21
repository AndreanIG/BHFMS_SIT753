<script setup>
import axios from 'axios';

defineProps({
    userRole: Number,
    invoice: Object
})

const emit = defineEmits(['showDetail','infoAlert']);

let showInvoiceDetail = (invoiceId) => {
    emit('showDetail', invoiceId);
}

let showPopUp = (invoiceStatus, invoiceId) => {
    emit('showPopUp', [invoiceStatus, invoiceId]);
}

const getFileName = (paymentDate, invoiceId) => {
  return new Date(paymentDate).toISOString().slice(0,10).replace(/-/g,"") + invoiceId + '.png';
}

const infoAlert = (message) => {
    emit('infoAlert', message)
}

let convertTime = (date) =>{
    return new Date(date).toLocaleDateString()
}

let convertAmount = (amount) => {
    return new Number(amount).toLocaleString("id-ID");
}

let updateInvoiceStatus = async (invoiceStatus, invoiceId) => {
    const formData = new FormData();
    formData.append('invoiceID',invoiceId);
    formData.append('invoiceStatus', invoiceStatus);
    const response = await axios.post('/updateInvoiceStatus', formData);
    
    const data = response.data;
    infoAlert(data.message);
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
        <img 
            class="px-5 w-7/12 h-[150px] appearance-none"
            v-if="invoice.payment_date"
            :src="`/storage/proofOfPayment/`+getFileName(invoice.payment_date, invoice.invoice_id)"
            alt="Proof Of Payment">
    </div>

    <div class="w-1/4 ">
        <input class="w-1/3 text-center" type="radio" :name="'checkInvoiceStatus'+invoice.invoice_id" value="approve" v-on:click="updateInvoiceStatus('Approved', invoice.invoice_id)">
        <input class="w-1/3 text-center" type="radio" :name="'checkInvoiceStatus'+invoice.invoice_id" value="reject"  v-on:click="showPopUp('Rejected', invoice.invoice_id)">
        <button class="w-1/3 bg-blue-500 text-white rounded-md px-4 py-2" v-on:click="showInvoiceDetail(invoice.invoice_id)">
            Details
            
        </button>
      
    </div>
</template>
