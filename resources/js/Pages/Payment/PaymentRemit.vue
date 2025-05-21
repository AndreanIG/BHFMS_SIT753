<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
import Header from '../../Shared/Header.vue';
import Footer from '../../Shared/Footer.vue';
import DetailBoxTenant from '../../Shared/Payment/DetailBoxTenant.vue';
import PopUpInputText from '../../Shared/Payment/PopUpInputText.vue';
import PopUpInputImage from '../../Shared/Payment/PopUpInputImage.vue';
import PaymentRemitTab from '../../Shared/Payment/PaymentRemitTab.vue';
import axios from 'axios';
defineProps({
  userRole: Number,
  invoiceList: Object,
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

const infoAlertText = ref('');
const popUpBoxText = ref(false);
const popUpBoxImage = ref(false);
const popUpDetail = ref({
  id: '',
  status: ''
})

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

let showPopUpText = (popUpData) => {
  popUpDetail.value.status = popUpData[0];
  popUpDetail.value.id = popUpData[1];
  popUpBoxText.value = true;
}

let showPopUpImage = (popUpData) => {
  popUpDetail.value.status = popUpData[0];
  popUpDetail.value.id = popUpData[1];
  popUpBoxImage.value = true;
}

const closeDetail = () => {
  detailBox.value = false;
  detailInvoice.value.price = 0;
  detailInvoice.value.invoice = [];
  detailInvoice.value.username = '';
  detailInvoice.value.boardingName = '';
}

const closePopUpText = () => {
  popUpBoxText.value = false;
  popUpDetail.value.id = '';
  popUpDetail.value.status = '';
}

const closePopUpImage = () => {
  popUpBoxImage.value = false;
  popUpDetail.value.id = '';
  popUpDetail.value.status = '';
}

const infoAlert = (message) => {
  infoAlertText.value = message;
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
              Remittance List
            </div>
        </section>
        <div
          v-if="infoAlertText"
          class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3"
          role="alert">
          <svg
              class="fill-current w-4 h-4 mr-2"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20">
            <path
              d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"
            />
          </svg>
          <p>{{ infoAlertText }}</p>
        </div>
        <section class="mx-2 my-5">
            <div class="flex justify-around text-center">
                <div class="w-1/4">
                    <h4>Date</h4>
                </div>
                <div class="w-1/4">
                    <h4>Amount</h4>
                </div>
                <div class="w-1/4">
                    <h4>Bank and Account Number</h4>
                </div>
                <div class="w-1/4 flex flex-row">
                    <p class="w-1/3">Approve</p>
                    <p class="w-1/3">Reject</p>
                    <p class="w-1/3"></p>
                </div>
            </div>
        </section>
        <section class="mx-2 my-3">
            <div v-for="(invoice) in invoiceList.data" class="flex text-center py-1">
              <PaymentRemitTab
              :user-role=userRole
              :invoice=invoice
              @showDetail="showDetail"
              @showPopUpDeclined="showPopUpText"
              @showPopUpSuccessful="showPopUpImage"
              />
            </div>
        </section>
      <div class="my-4 flex justify-center">
        <Link
          :href="invoiceList.prev_page_url"
          v-if="invoiceList.prev_page_url"
          class="px-2 py-1 bg-gray-300 rounded mr-2">
            Previous
        </Link>
        <Link
            :href="invoiceList.next_page_url"
            v-if="invoiceList.next_page_url"
            class="px-2 py-1 bg-gray-300 rounded ml-2">
            Next
        </Link>
      </div>
    </div>
    <DetailBoxTenant v-if="detailBox"
    :invoiceDetail = detailInvoice.invoice
    :price = detailInvoice.price
    :username = detailInvoice.username
    :boarding-name= detailInvoice.boardingName
    :user-role = this.userRole
    @closeDetail = "closeDetail"/>

    <PopUpInputImage v-if="popUpBoxImage"
    :pop-up-name="'Accept Proof'"
    :value= popUpDetail.status
    :label-name= "'Reason'"
    :id= popUpDetail.id
    :link-pop="'/updateTransferredStatus'"
    @closePopUp = "closePopUpImage"
    @infoAlert = "infoAlert"/>
    
    <PopUpInputText v-if="popUpBoxText" 
    :pop-up-name="'Reject Reason'"
    :value= popUpDetail.status
    :label-name= "'Reason'"
    :id= popUpDetail.id
    :link-pop="'/updateTransferredStatus'"
    @closePopUp = "closePopUpText"
    @infoAlert = "infoAlert"/>
    <Footer />
</template>