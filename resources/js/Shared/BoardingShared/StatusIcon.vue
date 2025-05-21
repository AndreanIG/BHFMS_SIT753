<script setup>
defineProps({
    boarding: Object,
});
</script>

<template>
    <td v-if="$page.props.user.role_id != 2" class="py-3 px-6 text-left">
        <span
            v-if="boarding.owner_status == 'approved'"
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        >
            Approved
        </span>

        <span
            v-else-if="boarding.owner_status == 'pending'"
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs"
            >Pending</span
        >

        <span
            v-else-if="boarding.owner_status == 'declined'"
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs"
            >Declined</span
        >

        <span
            v-else-if="boarding.owner_status == 'disabled'"
            class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs"
            >Disabled</span
        >

        <span
            v-else-if="boarding.owner_status == 'pending_payment'"
            class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs"
            >Pending Payment</span
        >

        <span
            v-else
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Banned</span
        >
    </td>

    <td v-else class="py-3 px-6 text-left">
        <span
            v-if="boarding.tenant_status == 'approved'"
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        >
            Approved
        </span>

        <span
            v-else-if="boarding.tenant_status == 'pending'"
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs"
            >Pending</span
        >

        <span
            v-else-if="boarding.tenant_status == 'declined'"
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs"
            >Declined</span
        >
        <span
            v-else-if="boarding.tenant_status == 'pending_payment'"
            class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs"
            >Pending Payment</span
        >

        <span
            v-else
            class="bg-slate-200 text-slate-600 py-1 px-3 rounded-full text-xs"
            >Done</span
        >
    </td>
</template>
