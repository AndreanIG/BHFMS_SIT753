<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref, watch } from "vue";
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import Pagination from "../../Shared/Pagination.vue";
import TableIconLinks from "../../Shared/TenantShared/TableIconLinks.vue";
import StatusIcon from "../../Shared/TenantShared/StatusIcon.vue";
import Legends from "../../Shared/TenantShared/Legends.vue";
defineProps({
    all_count: Number,
    approved: Number,
    declined: Number,
    pending: Number,
    done: Number,
    pendingPayment: Number,
    users: Object,
});

let search = ref("");
watch(search, (value) => {
    Inertia.get(
        "/tenantBoarding",
        { search: value },
        {
            preserveState: true,
        }
    );
});

let searchQuery = ref("");

watch(searchQuery, (value) => {
    Inertia.get(
        "/tenantBoarding",
        { searchQuery: value },
        {
            preserveState: true,
        }
    );
});
</script>

<template>
    <Header />
    <!-- component -->
    <!-- {{ $page.props.user.id }} -->
    <div class="overflow-x-auto">
        <div
            class="top-0 bg-gray-100 flex justify-center bg-gray font-sans overflow-hidden pt-2 min-h-[75vh]"
        >
            <!-- Filtering -->
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
                            <option value="checkout">
                                Checkout ({{ done }})
                            </option>
                            <option value="pending_payment">
                                Pending Payment ({{ pendingPayment }})
                            </option>
                        </select>
                    </div>
                </div>
                <Legends />

                <div class="bg-white shadow-md rounded my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">User name</th>
                                <th class="py-3 px-6 text-center">
                                    Boarding House name
                                </th>
                                <th class="py-3 px-6 text-center">Capacity</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr
                                v-for="(user, idx) in users.data"
                                class="border-b border-gray-200 bg-gray-50 hover:bg-gray-100"
                                :key="idx"
                            >
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span class="mr-2 font-medium"
                                            >{{ user.user_name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="py-3 px-6 text-center">
                                    <a href="#">{{ user.boarding_name }}</a>
                                </td>

                                <td class="py-3 px-6 text-center">
                                    <a href="#">{{ user.capacity }}</a>
                                </td>

                                <StatusIcon :user="user" />
                                <!-- Icon List -->
                                <TableIconLinks
                                    :currentID="user.id"
                                    :user="user"
                                />
                            </tr>
                        </tbody>
                    </table>
                    <Pagination
                        class="my-4 pb-4 flex justify-center"
                        :links="users.links"
                    />
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>
