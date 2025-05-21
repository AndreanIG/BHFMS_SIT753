<script setup>
import axios from 'axios';
import { ref } from 'vue';
const props = defineProps({
    labelName: String,
    popUpName: Array,
    value: String,
    id: String,
    linkPop: String,
});
const emit = defineEmits(['closePopUp','infoAlert']);
const closePopUp = () => {
    emit('closePopUp');
}
const infoAlert = (message) => {
    emit('infoAlert', message)
}

const selectedFile = ref(null);

const handleNewImage = (event) => {
  const file = event.target.files[0];
  selectedFile.value = file;
};

let confirm = async () => {
    
    const formData = new FormData();
    formData.append('image', selectedFile.value);
    formData.append('invoiceID', props.id);
    formData.append('invoiceStatus', props.value);
    const response = await axios.post(props.linkPop, formData);
    const data = await response.data;
    infoAlert(data.message);
    closePopUp();
}

</script>
<template>
    <div @scroll.prevent class="fixed flex w-full h-full bg-black/70  top-0 left-0 right-0 bottom-0 overflow-hidden items-center align-middle justify-center z-50 pointer-events-auto cursor-pointer" v-on:click="closePopUp" >
    </div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="flex flex-col bg-white/100 px-8 py-5">

            <h2 class="text-lg font-bold mb-4">{{ popUpName }}</h2>
            <label>{{ labelName }}:</label>
            <input type="file" class="border border-gray-300 px-2 py-1 rounded mb-4 w-full"
            accept="image/x-png,image/jpeg"
            ref="fileInput"
            @change="handleNewImage"
            >
            <div class="flex justify-between">
                <button @click="confirm" class="px-2 py-2 bg-blue-500 text-white rounded">Confirm</button>
                <button @click="closePopUp" class="px-2 py-2 bg-gray-300 text-white rounded">Cancel</button>
            </div>
        </div>
    </div>
  </template>