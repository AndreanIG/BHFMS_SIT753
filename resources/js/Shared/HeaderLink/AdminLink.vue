<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    hoverAdmin1: Boolean,
    hoverAdmin2: Boolean,
    hoverAdmin3: Boolean,
});
</script>

<template>
    <Link
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        href="/"
        >Homepage</Link
    >
    <div
        @mouseover="hoverAdmin1 = true"
        @mouseleave="hoverAdmin1 = false"
        class="h-10 w-full items-center text-center border-solid border-white z-50"
    >
        <div v-if="hoverAdmin1 == false" class="p-2 whitespace-nowrap">
            Boarding Management
        </div>
        <div
            v-if="hoverAdmin1 == true"
            class="whitespace-nowrap p-2 bg-indigo-900 z-50"
        >
            Boarding Management
        </div>
        <div
            class="bg-indigo-900 whitespace-nowrap z-50"
            v-if="hoverAdmin1 == true"
        >
            <ul>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/boardingAdmin">All Boarding</Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/boardingAdmin?search=pending"
                        >Pending Boarding
                    </Link>
                </li>
            </ul>
        </div>
    </div>

    <div
        @mouseover="hoverAdmin2 = true"
        @mouseleave="hoverAdmin2 = false"
        class="h-10 w-full items-center text-center border-solid border-white z-50"
    >
        <div v-if="hoverAdmin2 == false" class="p-2 whitespace-nowrap">
            Other Management
        </div>
        <div
            v-if="hoverAdmin2 == true"
            class="whitespace-nowrap p-2 bg-indigo-900 z-50"
        >
            Other Management
        </div>
        <div
            class="bg-indigo-900 whitespace-nowrap z-50"
            v-if="hoverAdmin2 == true"
        >
            <ul>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/facilityAll">All Facility </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/userAll">All User </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/boardingTypeAll">All Boarding Type </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/complainTypeAll">All Complain Type </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/transactionTypeAll"
                        >All Transaction Type
                    </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/paymentMethodAll">All Payment Method </Link>
                </li>
            </ul>
        </div>
    </div>
    <div
        @mouseover="hoverAdmin3 = true"
        @mouseleave="hoverAdmin3 = false"
        class="h-10 w-full items-center text-center border-solid border-white z-50"
    >
        <div v-if="hoverAdmin3 == false" class="p-2 whitespace-nowrap">
            Payment Management
        </div>
        <div
            v-if="hoverAdmin3 == true"
            class="whitespace-nowrap p-2 bg-indigo-900 z-50"
        >
            Payment Management
        </div>
        <div
            class="bg-indigo-900 whitespace-nowrap z-50"
            v-if="hoverAdmin3 == true"
        >
            <ul>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/checkInvoiceRequest">Check Invoice</Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/paymentSupport">Support Page </Link>
                </li>
                <li class="z-50 py-2 bg-indigo-900 hover:opacity-75">
                    <Link href="/remitPayment">Remit Page </Link>
                </li>
            </ul>
        </div>
    </div>
    <Link
        class="h-10 w-full text-center p-2 whitespace-nowrap z-50 bg-indigo-900 hover:opacity-75"
        href="chat">
            Chat
    </Link>
</template>
