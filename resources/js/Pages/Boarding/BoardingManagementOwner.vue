<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref, watch } from "vue";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import TableIconLinks from "../../Shared/BoardingShared/TableIconLinks.vue";
import StatusIcon from "../../Shared/BoardingShared/StatusIcon.vue";
import Pagination from "../../Shared/Pagination.vue";
import Legends from "../../Shared/BoardingShared/Legends.vue";
defineProps({
    all_count: Number,
    approved: Number,
    declined: Number,
    pending: Number,
    banned: Number,
    disabled: Number,
    boardings: Object,
});

let search = ref("");
watch(search, (value) => {
    Inertia.get(
        "/boardingOwner",
        { search: value },
        {
            preserveState: true,
        }
    );
});

let searchQuery = ref("");

watch(searchQuery, (value) => {
    Inertia.get(
        "/boardingOwner",
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
            class="top-0 bg-gray-100 flex justify-center bg-gray font-sans overflow-hidden pt-2 min-h-[75vh]"
        >
            <div class="flow-root">
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

                <div class="mt-2 relative">
                    <a href="/boarding/create">
                        <div
                            class="rounded border block bg-white border-gray-400 text-gray-700 py-2 px-4 flex"
                        >
                            <button
                                class="flex w-6 h-6 mr-5 text-base bg-blue-400 items-center font-bold justify-center text-white rounded-xl font-mono"
                            >
                                +
                            </button>

                            <h3 class="self-center">Add New Boarding</h3>
                        </div>
                    </a>
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
                        <select
                            class="rounded border block bg-white border-gray-400 text-gray-700 py-2 px-4"
                            v-model="search"
                        >
                            <option value="" selected hidden>
                                Filter Status
                            </option>
                            <option value="all">All ({{ all_count }})</option>
                            <option value="approved">
                                Approved ({{ approved }})
                            </option>
                            <option value="pending">
                                Pending ({{ pending }})
                            </option>
                            <option value="declined">
                                Declined ({{ declined }})
                            </option>
                            <option value="disabled">
                                Disabled ({{ disabled }})
                            </option>
                            <option value="banned">
                                Banned ({{ banned }})
                            </option>
                        </select>
                    </div>
                </div>

                <Legends />

                <div class="bg-white shadow-md rounded my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">
                                    Boarding House Name
                                </th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-center">
                                    Boarding Owner Name
                                </th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr
                                v-for="(boarding, idx) in boardings.data"
                                class="border-b border-gray-200 bg-gray-50 hover:bg-gray-100"
                                :key="idx"
                            >
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span class="mr-2 font-medium"
                                            >{{ boarding.boarding_name }}
                                        </span>
                                    </div>
                                </td>

                                <StatusIcon :boarding="boarding" />

                                <td class="py-3 px-6 text-center">
                                    <a href="#">{{ boarding.user_name }}</a>
                                </td>

                                <!-- Icon List -->
                                <TableIconLinks
                                    :currentID="boarding.boarding_id"
                                    :boarding="boarding"
                                />
                            </tr>
                        </tbody>
                    </table>
                    <Pagination
                        class="my-4 pb-4 flex justify-center"
                        :links="boardings.links"
                    />
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>
