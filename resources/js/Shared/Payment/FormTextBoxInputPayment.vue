<script setup>
defineProps({
    labelName: String,
    inputType: String,
    modelValue: String,
    minValue: String,
})

defineEmits(['update:modelValue'])
</script>

<template>
    <label class="block mt-2">{{ labelName }}</label>
    <input
        @input="$emit('update:modelValue', $event.target.value)"
        :type="inputType"
        :placeholder="labelName"
        :value="modelValue"
        :min="minValue"
        class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
    />
</template>
