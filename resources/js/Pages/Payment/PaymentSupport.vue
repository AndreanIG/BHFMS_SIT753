<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import DetailBoxTenant from '../../Shared/Payment/DetailBoxTenant.vue';
import PaymentSupportTab from '../../Shared/Payment/PaymentSupportTab.vue';
import axios from 'axios';
defineProps({
  userRole: Number,
  paymentList: Object,
})

defineExpose({
  detailInvoice
});

const detailBox = ref(false);
const detailInvoice = ref({
  invoice: [],
  price: 0,
  username: '',
  boardingName: ''
});

let showDetail = (invoice_id) => {
  axios.post('/getInvoiceData', {
    invoice_id: invoice_id,
  })
  .then((response) => {
    const data = response.data;
    detailInvoice.value.price = convertAmount(data[0]);
    detailInvoice.value.invoice = data[1];
    detailInvoice.value.username = data[2];
    detailInvoice.value.boardingName = data[3];
  })

  detailBox.value = true;
}

let closeDetail = () => {
  detailBox.value = false;
  detailInvoice.value.price = 0;
    detailInvoice.value.invoice = [];
    detailInvoice.value.username = '';
    detailInvoice.value.boardingName = '';
}

let convertAmount = (amount) => {
    return new Number(amount).toLocaleString("id-ID");
}

</script>

<template>
    <Header />
    <div class="z-10 my-4 min-h-[75vh]">
        <section class="flex text-center align-middle mx-10">
            <div class="text-2xl font-semibold self-center">
              Payment Support List
            </div>
        </section>
        <section class="mx-2 my-5">
            <div class="flex justify-around text-center">
                <div class="w-1/4">
                    <h4>Status</h4>
                </div>
                <div class="w-1/4">
                    <h4>Date</h4>
                </div>
                <div class="w-1/4">
                    <h4>Boarding House</h4>
                </div>
                <div class="w-1/4">
                </div>
            </div>
        </section>
        <section class="mx-2 my-3">
            <div v-for="(payment) in paymentList.data" class="flex justify-around text-center py-1">
             
              <PaymentSupportTab
                :payment=payment
                :userRole="userRole"
                @showDetail="showDetail" />
            </div>
        </section>
      <div class="my-4 flex justify-center">
        <Link
          :href="paymentList.prev_page_url"
          v-if="paymentList.prev_page_url"
          class="px-2 py-1 bg-gray-300 rounded mr-2">
            Previous
        </Link>
        <Link
            :href="paymentList.next_page_url"
            v-if="paymentList.next_page_url"
            class="px-2 py-1 bg-gray-300 rounded ml-2">
            Next
        </Link>
      </div>
    </div>
    <DetailBoxTenant v-if = "detailBox"
    :invoiceDetail = detailInvoice.invoice
    :price = detailInvoice.price
    :username = detailInvoice.username
    :boarding-name= detailInvoice.boardingName
    :user-role = this.userRole
    @closeDetail = "closeDetail" />
    <Footer />
</template>