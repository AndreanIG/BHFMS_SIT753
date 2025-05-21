<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Footer from "../../../Shared/Footer.vue";
import Header from "../../../Shared/Header.vue";

defineProps({
    boardingHouseList: Array,
});
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <div class="border border-slate-200 space-y-2 px-4 py-6">
            <div class="flex justify-between">
                <div class="semibold text-2xl text-indigo-700">COMPLAIN</div>
            </div>
            <Link
                href="/complain/house"
                :data="{ id: boardingHouse.id }"
                v-for="(boardingHouse, key) in boardingHouseList"
                :key="key"
                class="border border-slate-200 flex justify-between items-center px-4 hover:bg-slate-50"
            >
                <div class="w-full flex justify-between items-center">
                    <div class="w-1/2">
                        <div class="font-semibold text-lg">
                            {{ boardingHouse.boarding_name }}
                        </div>
                        <div class="break-normal">
                            {{ boardingHouse.address }}
                        </div>
                    </div>
                    <div class="w-1/2 flex justify-end">
                        <div class="text-right">
                            <p>pending: {{ boardingHouse.complain_pending_count }}</p>
                            <p>on progress: {{ boardingHouse.complain_on_progress_count }}</p>
                            <p>finished: {{ boardingHouse.complain_finished_count }}</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </section>
    <Footer />
</template>
