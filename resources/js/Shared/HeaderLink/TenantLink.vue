<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    hoverTenant: Boolean,
});
</script>

<template>
    <Link
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        href="/"
        >Homepage</Link
    >
    <Link
        href="/boardingTenant"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >My Boarding House
    </Link>
    <Link
        href="/paymentHistory"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Payment History
    </Link>
    <Link
        href="/complain"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Complain List
    </Link>
    <Link
        href="/chat"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Chat
    </Link>
</template>
