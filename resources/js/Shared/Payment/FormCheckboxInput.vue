<script setup>
defineProps({
    labelName: String,
    defaultValue: Number
})

</script>

<template>
<div class="flex flex-row my-2">
    <input
        @input="$emit('update:modelValue', $event.target.value)"
        type="checkbox"
        class="mr-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
        value="true"
        checked
        v-if="defaultValue!=null"
    />
    <input
        @input="$emit('update:modelValue', $event.target.value)"
        type="checkbox"
        class="mr-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
        value="true"
        v-else
    />

    <label class="block">{{ labelName }}</label>
</div>
    
</template>
