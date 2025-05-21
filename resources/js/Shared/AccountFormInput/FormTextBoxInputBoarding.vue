<script setup>
defineProps({
    labelName: String,
    inputType: String,
    modelValue: String,
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <input
        @input="$emit('update:modelValue', $event.target.value)"
        :type="inputType"
        :placeholder="labelName"
        :value="modelValue"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
    />
    <div
        v-if="form.errors.modelValue"
        v-text="form.errors.modelValue"
        class="text-red-500 text-xs mt-1"
    />
</template>
