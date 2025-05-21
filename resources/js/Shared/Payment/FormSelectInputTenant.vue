<script setup>
defineProps({
    labelName: String,
    optionList: Array,
    labelDesc: String,
    event: Event,
    defaultUsername: String,
    defaultEmail: String
});
defineEmits(['update:modelValue'])
</script>

<template>
    <label class="block  mt-2">{{ labelName }}</label>
    <select
        @change="$event"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="labelName"
        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
    >

        <option v-if="defaultEmail==null" value="" disabled selected hidden>{{ labelDesc }}</option>
        <option v-if="defaultEmail!=null" :value="defaultEmail" selected hidden>{{ defaultUsername }}</option>
        
        <option v-for="option in optionList" :value="option.email">{{ option.user_name }}</option>
    </select>
</template>
