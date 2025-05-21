<script setup>
import Footer from "../../Shared/Footer.vue";
import Header from "../../Shared/Header.vue";
import { Inertia } from "@inertiajs/inertia";

defineProps({
    complainType: String,
    complain: Object,
});

let setStatus = (id, status) => {
    Inertia.post("/complain/status", {
        id: id,
        status: status,
    });
};

let redirectBack = () => {
    window.history.back();
}
</script>

<template>
    <Header />
    <section class="min-h-[75vh] p-10">
        <div class="border border-slate-200 space-y-2 px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="semibold text-2xl text-indigo-700">
                    Complain Detail
                </div>
                <div>
                    <button @click="redirectBack()" class=" bg-red-600 text-white hover:bg-red-800 p-2 flex justify-center items-center">
                        Back
                    </button>
                </div>
            </div>
            <div>
                <label class="block font-semibold">Status</label>
                <div
                    v-if="$page.props.user.role_id == 2"
                    class="w-[6rem] h-full flex justify-center text-center rounded-md whitespace-nowrap"
                    :class="{
                        'text-white bg-slate-500':
                            complain.complain_status == 'pending',
                        'text-white bg-yellow-500':
                            complain.complain_status == 'on progress',
                        'text-white bg-green-500':
                            complain.complain_status == 'finished',
                    }"
                >
                    {{ complain.complain_status }}
                </div>
                <div
                    v-if="
                        $page.props.user.role_id == 3 ||
                        $page.props.user.role_id == 4
                    "
                    class="w-1/6 flex justify-between space-x-2"
                >
                    <button
                        v-if="complain.complain_status != 'finished'"
                        class="w-[6rem] text-white rounded-md whitespace-nowrap"
                        :class="{
                            'bg-slate-700 hover:cursor-not-allowed':
                                complain.complain_status == 'pending',
                            'bg-slate-500':
                                complain.complain_status != 'pending',
                        }"
                        :disabled="complain.complain_status == 'pending'"
                        @click="setStatus(complain.id, 'pending')"
                    >
                        Pending
                    </button>
                    <button
                        v-if="complain.complain_status != 'finished'"
                        class="w-[6rem] text-black rounded-md whitespace-nowrap"
                        :class="{
                            'bg-yellow-700 hover:cursor-not-allowed':
                                complain.complain_status == 'on progress',
                            'bg-yellow-500':
                                complain.complain_status != 'on progress',
                        }"
                        :disabled="complain.complain_status == 'on progress'"
                        @click="setStatus(complain.id, 'on progress')"
                    >
                        On Progress
                    </button>
                    <button
                        class="w-[6rem] text-black rounded-md whitespace-nowrap"
                        :class="{
                            'bg-green-500 hover:cursor-not-allowed disabled':
                                complain.complain_status == 'finished',
                            'bg-green-400':
                                complain.complain_status != 'finished',
                        }"
                        :disabled="complain.complain_status == 'finished'"
                        @click="setStatus(complain.id, 'finished')"
                    >
                        Finished
                    </button>
                </div>
            </div>
            <div>
                <label class="block font-semibold">Complain Type</label>
                <input
                    :value="complainType"
                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                    disabled
                />
            </div>
            <div>
                <label class="block font-semibold">Complain Description</label>
                <textarea
                    :value="complain.complain_desc"
                    class="w-full h-[20vh] p-2.5 border border-gray-300 rounded-lg"
                    placeholder="Please describe your problem"
                    disabled
                />
            </div>
            <div>
                <label class="block font-semibold">Photo/Files</label>
                <img
                    :src="complain.complain_image_url"
                    class="w-40 h-40 object-cover"
                />
            </div>
        </div>
    </section>
    <Footer />
</template>
