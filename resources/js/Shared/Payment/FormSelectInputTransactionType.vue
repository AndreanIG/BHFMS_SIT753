<script setup>
defineProps({
    labelName: String,
    optionList: Array,
    labelDesc: String,
    defaultText: String,
    defaultValue: Number
});

defineEmits(['update:modelValue'])
</script>

<template>
    <label class="block  mt-2">{{ labelName }}</label>
    <select
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="labelName"
        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
    >
    <option v-if="defaultValue !=null" :value=optionList[defaultValue] hidden>{{ optionList[defaultValue] }}</option>
    <option v-else value="" disabled selected hidden>{{ defaultText }}</option>
    <option v-for="option in optionList" :value="option">{{ option }}</option>
    </select>
</template>
