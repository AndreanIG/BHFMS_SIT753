<script setup>
import PaymentTypeText from './PaymentTypeText.vue';
import { ref } from 'vue';
const props = defineProps({
    emit: Function,
    labelName: String,
    optionList: Array,
    defaultText: String,
});

const emit = defineEmits(['update:value']);
const valuePayment = ref('');

const updateValuePayment = () => {
    emit('update:value', valuePayment.value);
};

</script>

<template>
    <label class="block">{{ labelName }}</label>
    <div>
        <select
        v-model="valuePayment"
        :placeholder="labelName"
        @change="updateValuePayment"
        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
        
    >
        <option value="" disabled selected hidden>{{ defaultText }}</option>
        <option v-for="option in optionList" :value="option">{{ option }}</option>
        </select>
    </div>
    <PaymentTypeText
    :payment-method="valuePayment" />
</template>
