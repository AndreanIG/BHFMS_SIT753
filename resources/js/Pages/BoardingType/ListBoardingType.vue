<script setup>
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, reactive, watch } from "vue";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import Pagination from "../../Shared/Pagination.vue";
import Legends from "../../Shared/AdminLegends.vue";
defineProps({
    boardingTypes: Object,
});
let mouseover = ref(false);

let popup = reactive({
    show: false,
    boardingType_id: 0,
    boardingType_detail_name: "",
});

let enableDisablePopup = (id, name) => {
    popup.show = !popup.show;
    popup.boardingType_id = id;
    popup.boardingType_detail_name = name;
};

const deleteboardingType = (idx) => {
    Inertia.post(`/boardingType/delete/${idx}`);
};

let searchQuery = ref("");

watch(searchQuery, (value) => {
    Inertia.get(
        "/boardingTypeAll",
        { searchQuery: value },
        {
            preserveState: true,
        }
    );
});
</script>

<template>
    <Header />
    <div class="overflow-x-auto">
        <div
            class="px-6 top-0 bg-gray-100 flex justify-center bg-gray font-sans overflow-hidden pt-2 min-h-[75vh]"
        >
            <div class="flow-root w-11/12">
                <div
                    v-if="$page.props.flash.message"
                    class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3"
                    role="alert"
                >
                    <svg
                        class="fill-current w-4 h-4 mr-2"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"
                        />
                    </svg>
                    <p>{{ $page.props.flash.message }}</p>
                </div>
                <div class="flow-root">
                    <div class="mt-2 float-left">
                        <div
                            class="relative flex w-full flex-wrap items-stretch"
                        >
                            <input
                                type="text"
                                class="rounded border block bg-white border-gray-400 text-gray-700 py-2 px-4"
                                v-model="searchQuery"
                                placeholder="Search..."
                            />
                        </div>
                    </div>
                    <div class="mt-2 float-right">
                        <a href="/boardingType/create">
                            <div
                                class="rounded border block bg-white border-gray-400 text-gray-700 py-2 px-4 flex"
                            >
                                <button
                                    class="flex w-6 h-6 mr-5 text-base bg-blue-400 items-center font-bold justify-center text-white rounded-xl font-mono"
                                >
                                    +
                                </button>

                                <h3 class="self-center">
                                    Add New Boarding Type
                                </h3>
                            </div>
                        </a>
                    </div>
                </div>
                <Legends />

                <div class="bg-white shadow-md rounded my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="uppercase text-sm leading-normal">
                                <th
                                    class="py-3 px-6 text-center whitespace-nowrap"
                                >
                                    Boarding Type Name
                                </th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-600 text-sm font-light">
                            <tr
                                v-for="(
                                    boardingType, idx
                                ) in boardingTypes.data"
                                class="border-b border-gray-200 bg-gray-50 hover:bg-gray-100"
                                :key="idx"
                            >
                                <td class="py-3 px-6 text-center">
                                    {{ boardingType.boarding_type_name }}
                                </td>

                                <!-- Icon List -->
                                <td class="py-3 px-6 text-center">
                                    <div
                                        class="flex item-center justify-center"
                                    >
                                        <div
                                            class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110"
                                        >
                                            <Link
                                                :href="`boardingType/update/${boardingType.id}`"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                    />
                                                </svg>
                                            </Link>
                                        </div>

                                        <div
                                            class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110"
                                            @mouseover="mouseover = true"
                                            @mouseleave="mouseover = false"
                                            @click="
                                                enableDisablePopup(
                                                    boardingType.id,
                                                    boardingType.boarding_type_name
                                                )
                                            "
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                ></circle>
                                                <line
                                                    x1="15"
                                                    y1="9"
                                                    x2="9"
                                                    y2="15"
                                                ></line>
                                                <line
                                                    x1="9"
                                                    y1="9"
                                                    x2="15"
                                                    y2="15"
                                                ></line>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination
                        class="my-4 pb-4 flex justify-center"
                        :links="boardingTypes.links"
                    />
                </div>
            </div>
        </div>
    </div>
    <div
        v-if="popup.show"
        class="fixed inset-0 grid place-items-center"
        style="background: rgba(0, 0, 0, 0.4)"
        @click="popup.show = false"
    >
        <div class="bg-white w-5/12 rounded">
            <header class="text-xl m-3">Confirmation</header>
            <div class="mx-3">
                Delete {{ popup.boardingType_detail_name }} from database ?
            </div>
            <button
                class="bg-blue-500 hover:bg-blue-600 rounded p-2 ml-3 my-3 text-white"
                type="button"
                @click="deleteboardingType(popup.boardingType_id)"
            >
                Confirm
            </button>
            <button
                class="bg-red-500 hover:bg-red-600 rounded p-2 m-3 text-white"
                type="button"
                @click="popup.show = false"
            >
                Back
            </button>
        </div>
    </div>
    <Footer />
</template>
