<script setup>
import { Link } from "@inertiajs/inertia-vue3";
defineProps({
    currentID: Number,
    user: Object,
});

const deleteuser = (idx) => {
    $inertia.put(`/user/delete/${idx}`);
};
</script>

<template>
    <td class="py-3 px-6 text-center">
        <div class="flex item-center justify-center">
            <div
                class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110"
            >
                <Link :href="`/tenantBoarding/read/${currentID}`">
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
                </Link>
            </div>

            <div class="flex">
                <div
                    v-if="user.tenant_status == 'pending'"
                    class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110 mt-0.5"
                >
                    <Link :href="`tenantBoarding/request/${currentID}`">
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
                    </Link>
                </div>
            </div>
        </div>
    </td>
</template>
