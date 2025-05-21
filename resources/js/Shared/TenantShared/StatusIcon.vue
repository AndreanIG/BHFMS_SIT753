<script setup>
defineProps({
    user: Object,
});
</script>

<template>
    <td class="py-3 px-6 text-left">
        <span
            v-if="user.tenant_status == 'approved'"
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        >
            Approved
        </span>

        <span
            v-else-if="user.tenant_status == 'pending'"
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs"
            >Pending</span
        >

        <span
            v-else-if="user.tenant_status == 'declined'"
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs"
            >Declined</span
        >

        <span
            v-else-if="user.tenant_status == 'checkout'"
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Checkout</span
        >
        <span
            v-else-if="user.tenant_status == 'pending_payment'"
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Pending Payment</span
        >
    </td>
</template>
