<script setup>
import { ref, reactive, watch } from "vue";
defineProps({
    boarding: Object,
});

const data = reactive({
    showList: false,
});

const toggleList = () => {
    data.showList = !data.showList;
};
</script>

<template>
    <div class="flow-root">
        <div class="mt-2 float-right">
            <button
                @click="toggleList"
                class="rounded border block bg-white border-gray-400 text-gray-700 py-2 px-4 w-full"
            >
                Icons Legends
            </button>

            <ul
                v-if="data.showList"
                class="absolute z-50 bg-white mt-2 border border-gray-400 text-gray-700"
            >
                <li class="p-2 flex">
                    <div class="w-4 mr-2 transform">
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
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                    </div>
                    <span class="mr-2 text-xs bold">See Details</span>
                </li>
                <li class="p-2 flex">
                    <div class="w-4 mr-2 transform">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"
                            ></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                    </div>
                    <span class="mr-2 text-xs bold">See Request</span>
                </li>
            </ul>
        </div>
    </div>
</template>
