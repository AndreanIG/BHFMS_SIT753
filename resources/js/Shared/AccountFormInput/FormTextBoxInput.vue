<script setup>
defineProps({
    labelName: String,
    inputType: String,
    modelValue: String,
})

defineEmits(['update:modelValue'])
</script>

<template>
    <label class="block">{{ labelName }}</label>
    <input
        @input="$emit('update:modelValue', $event.target.value)"
        :type="inputType"
        :placeholder="labelName"
        :value="modelValue"
        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
    />
</template>
