<script setup>
defineProps({
    user: Object,
});
</script>

<template>
    <td class="py-3 px-6 text-center">
        <span
            v-if="user.user_role_id == '1'"
            class="bg-purple-200 text-green-600 py-1 px-3 rounded-full text-xs"
        >
            Admin
        </span>

        <span
            v-else-if="user.user_role_id == '2'"
            class="bg-red-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Tenant</span
        >

        <span
            v-else-if="user.user_role_id == '3'"
            class="bg-blue-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Owner</span
        >

        <span
            v-else-if="user.user_role_id == '4'"
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Manager</span
        >
    </td>
</template>
