<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    hoverOwner: Boolean,
});
</script>

<template>
    <Link
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        href="/"
        >Homepage</Link
    >
    <div
        @mouseover="hoverOwner = true"
        @mouseleave="hoverOwner = false"
        class="px-2 h-10 w-full items-center text-center border-solid border-white z-50"
    >
        <div v-if="hoverOwner == false" class="p-2 whitespace-nowrap">
            Tenant Management
        </div>
        <div
            v-if="hoverOwner == true"
            class="whitespace-nowrap py-2 bg-indigo-900 z-50 px-2"
        >
            Tenant Management
        </div>
        <div
            class="bg-indigo-900 whitespace-nowrap z-50"
            v-if="hoverOwner == true"
        >
            <ul>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/tenantBoarding">Tenant List</Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/tenantBoarding?search=pending"
                        >Tenant Request</Link
                    >
                </li>
            </ul>
        </div>
    </div>

    <Link
        v-if="$page.props.user.role_id == 3"
        href="/boardingOwner"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Boarding Management
    </Link>
    <Link
        v-if="$page.props.user.role_id == 4"
        href="/boardingManager"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Boarding Management
    </Link>
    <Link
        href="/complain/owner"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        >Complain Management
    </Link>
    <Link
        href="/chat"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
    >
        Chat</Link
    >
    <Link
        href="/paymentHistory"
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
    >
        Payment History</Link
    >
</template>
