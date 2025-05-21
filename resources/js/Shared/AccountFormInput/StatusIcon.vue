<script setup>
defineProps({
    user: Object,
});
</script>

<template>
    <td class="py-3 px-6 text-center">
        <span
            v-if="user.user_status == 'active'"
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        >
            Active
        </span>

        <span
            v-else-if="user.user_status == 'banned'"
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Banned</span
        >
    </td>
</template>
