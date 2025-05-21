<script setup>
import Footer from "../../Shared/Footer.vue";
import Header from "../../Shared/Header.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    complainList: Object,
});
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <div class="border border-slate-200 space-y-2 px-4 py-6">
            <div class="flex justify-between">
                <div class="semibold text-2xl text-indigo-700">COMPLAIN</div>
                <Link
                    href="/complain/create"
                    class="w-8 h-8 bg-blue-600 flex justify-center items-center text-center text-2xl text-white"
                >
                    +
                </Link>
            </div>
            <Link
                href="/complain/detail"
                :data="{ id: complain.id }"
                v-for="(complain, key) in complainList"
                :key="key"
                class="border border-slate-200 flex justify-between items-center px-4 hover:bg-slate-50"
            >
                <div>
                    <div>
                        {{ complain.boarding_house_name }}
                    </div>
                    <div>
                        {{ complain.complain_type_name }}
                    </div>
                </div>
                <div>
                    <div class="w-6 h-6 rounded-full"
                        :class="{
                            'bg-slate-500':
                                complain.complain_status == 'pending',
                            'bg-yellow-500':
                                complain.complain_status == 'on progress',
                            'bg-green-500':
                                complain.complain_status == 'finished',
                        }"
                    />
                </div>
            </Link>
        </div>
    </section>
    <Footer />
</template>
