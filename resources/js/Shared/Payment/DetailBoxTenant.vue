<script setup>
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    invoiceDetail: Object,
    price: String,
    userRole: Number,
    username: String,
    boardingName: String,
});

const emit = defineEmits(['closeDetail']);

const query = new URLSearchParams(window.location.search);

let closeDetail = () => {
    emit('closeDetail');
}

let convertTime = (date) =>{
    return new Date(date).toLocaleDateString();
}

let redirect = (url) => {
    Inertia.get(url);
}

const getFileName = (paymentDate, invoiceId) => {
  return new Date(paymentDate).toISOString().slice(0,10).replace(/-/g,"") + invoiceId + '.png';
}
</script>

<template>
   <div @scroll.prevent class="fixed flex w-full h-full bg-black/70  top-0 left-0 right-0 bottom-0 overflow-hidden items-center align-middle justify-center z-50 pointer-events-auto cursor-pointer" v-on:click="closeDetail">
   </div>
   <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50">
            <div class="relative flex flex-col bg-white/100 opacity-100">
                <div class="flex flex-row justify-between p-2">
                    <h2>
                        Detail Payment
                    </h2>
                    <button v-on:click="closeDetail">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="h-full w-full flex flex-row">
                    <div class="flex flex-col w-4/6 border px-5 py-2">
                        <div class="w-full space-y-4">
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Invoice No</p>
                                    <p>:</p>
                                </div>
                                <Link class="px-5 text-blue-500  w-7/12" href="">{{ invoiceDetail.invoice_id }} </Link>
                                
                            </div>
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Tennant</p>
                                    <p>:</p>
                                </div>
                                <p class="px-5 w-7/12">{{ username }}</p>
                            </div>
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Boarding House</p>
                                    <p>:</p>
                                </div>
                                <p class="px-5 w-7/12">{{ boardingName }}</p>
                            </div>
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Transaction Type</p>
                                    <p>:</p>
                                </div>
                                <p class="px-5 w-7/12">{{ invoiceDetail.transaction_type_name }}</p>
                            </div>
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Payment Date</p>
                                    <p>:</p>
                                </div>
                                <p class="px-5 w-7/12">{{ convertTime(invoiceDetail.payment_date) }}</p>
                            </div>
                            <div class="flex flex-row">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Status</p>
                                    <p>:</p>
                                </div>
                                <p class="px-5 w-7/12">{{ invoiceDetail.payment_status }}</p>
                            </div>
                            <div class="flex flex-row"
                            v-if="invoiceDetail.payment_status=='Processing'||invoiceDetail.payment_status=='Approved'">
                                <div class="flex flex-row w-5/12 justify-between">
                                    <p>Proof Of Payment</p>
                                    <p>:</p>
                                </div>
                                <img 
                                class="px-5 w-7/12 h-[150px] appearance-none"
                                v-if="invoiceDetail.payment_date"
                                :src="`/storage/proofOfPayment/`+getFileName(invoiceDetail.payment_date, invoiceDetail.invoice_id)"
                                alt="Proof Of Payment">
                            </div>
                        </div>
                        <div class="flex flex-row justify-between my-10">
                            <p>Total Price</p>
                            <p>Rp {{ price }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col border w-2/6 px-3 items-center" v-if="userRole!=1">
                        <button class="mt-4 mb-2 bg-blue-500 py-1 w-24 text-white rounded-full" v-if="(invoiceDetail.payment_status=='Pending' || invoiceDetail.payment_status=='Late') && userRole==2" @click="redirect('/pay?order='+invoiceDetail.invoice_id)">Pay</button>
                        
                        <button class="my-2 bg-blue-500 py-1 w-24 text-white rounded-full" @click="redirect('/chat')">Chat</button>
                        
                        <button class="my-2 bg-blue-500 py-1 w-24 text-white rounded-full" v-if="userRole==2"  @click="redirect('/complain')">Complain</button>
                        <button class="my-2 bg-blue-500 py-1 w-24 text-white rounded-full" v-if="(userRole==4||userRole==3) && (invoiceDetail.payment_transferred_status !='Successful' && invoiceDetail.payment_status !='Canceled') && invoiceDetail.transaction_type_name != 'Down Payment'" @click="redirect('/paymentBoarding/cancel?order='+invoiceDetail.invoice_id+'&boarding='+query.get('boarding'))">Cancel</button>
                        <button class="my-2 bg-blue-500 py-1 w-24 text-white rounded-full" v-if="userRole==4||userRole==3 && invoiceDetail.payment_status =='Pending'" @click="redirect('/paymentBoarding/edit?order='+invoiceDetail.invoice_id+'&boarding='+query.get('boarding'))">Edit</button>
                    </div>
                </div>
            </div>
        </div>
</template>