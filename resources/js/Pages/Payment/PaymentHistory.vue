<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
import PaymentHistoryTab from '../../Shared/Payment/PaymentHistoryTab.vue';
import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import DetailBoxTenant from '../../Shared/Payment/DetailBoxTenant.vue';
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
              Payment List
            </div>
        </section>
        <div
          v-if="$page.props.flash.message"
          class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3"
          role="alert">
          <svg
            class="fill-current w-4 h-4 mr-2"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
          >
            <path
                d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"
            />
          </svg>
          <p>{{ $page.props.flash.message }}</p>
        </div>
        <section class="mx-9 my-5">
            <div class="flex justify-around text-center">
                <div class="w-1/4">
                    <h4>Status</h4>
                </div>
                <div class="w-1/4">
                    <h4>Date</h4>
                </div>
                <div class="w-1/4">
                    <h4 v-if="userRole == 3 || userRole == 4">Tenant Name</h4>
                    <h4 v-else-if="userRole == 2">Boarding House</h4>
                    <h4 v-else>Boarding House</h4>
                </div>
                <div class="w-1/4">
                </div>
            </div>
        </section>
        <section class="mx-2 my-3">
            <div v-for="(payment) in paymentList.data" class="flex justify-around text-center py-1">
              <PaymentHistoryTab
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