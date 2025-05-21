<script setup>
defineProps({
    labelName: String,
    inputType: String,
    modelValue: String,
    errorMessage: String,
    placeholder: String,
    readOnly: Boolean,
    minValue: String,
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <label class="block text-gray-700 text-sm font-bold mb-2">
        {{ labelName }}
    </label>
    <input
        v-if="readOnly == true"
        @input="$emit('update:modelValue', $event.target.value)"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :type="inputType"
        :value="modelValue"
        :placeholder="placeholder"
        readonly
    />
    <input
        v-else
        @input="$emit('update:modelValue', $event.target.value)"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :type="inputType"
        :value="modelValue"
        :placeholder="placeholder"
        :min="minValue"
    />
    <div
        v-if="errorMessage"
        v-text="errorMessage"
        class="text-red-500 text-xs mt-1"
    />
</template>
