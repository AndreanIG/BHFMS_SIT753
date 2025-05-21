<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Footer from "../../Shared/Footer.vue";
import Header from "../../Shared/Header.vue";

defineProps({
    boardingHouseList: Array,
});
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <div class="border border-slate-200 space-y-2 px-4 py-6">
            <div class="flex justify-between">
                <div class="semibold text-2xl text-indigo-700">REVIEW BOARDING HOUSE</div>
            </div>
            <Link
                href="/review/create"
                :data="{ id: boardingHouse.id }"
                v-for="(boardingHouse, key) in boardingHouseList"
                :key="key"
                class="border border-slate-200 flex justify-between items-center px-4 hover:bg-slate-50"
            >
                <div>
                    <div class="font-semibold text-lg">
                        {{ boardingHouse.boarding_name }}
                    </div>
                    <div>
                        {{ boardingHouse.address }}
                    </div>
                </div>
            </Link>
        </div>
    </section>
    <Footer />
</template>
