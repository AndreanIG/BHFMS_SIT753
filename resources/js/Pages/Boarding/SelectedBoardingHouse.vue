<script setup>
import Header from "../../Shared/Header.vue";
import Footer from "../../Shared/Footer.vue";
import Carousel from "../../Shared/Carousel/Carousel.vue";
import FormTextBoxInput from "../../Shared/AccountFormInput/FormTextBoxInput.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

let props = defineProps({
    boardingHouseDetail: Object,
    facilityList: Object,
    images: Array,
    ownerName: String,
    ownerPicture: String,
    currVacancy: Number,
    isAvailable: Boolean,
    reviews: Array,
    averageRating: String,
    totalReviewCount: Number,
    ratingStar: Array,
    isWishlisted: Boolean,
    boardingManagedBySameOwner: Array,
});
let buttonClicked = ref(false);
let totalPrice = ref(null);
let mouseover = ref(false);
const popup = reactive({
    wishlist: {
        success: {
            show: false,
        },
        remove: {
            show: false,
        },
    },
});

let strokeColor = () => {
    return mouseover.value == false ? "#000000" : "#9b9b9b";
};

let checkWishlisted = () => {
    return props.isWishlisted ? "full" : "none";
};

let form = useForm({
    startDate: "",
    endDate: "",
});

let submit = (idx) => {
    form.post(`/boarding/detail/rent/${idx}`, {});
    buttonClicked.value = true;
};

let addToWishlist = () => {
    Inertia.post(
        "/wishlist/add",
        {
            id: props.boardingHouseDetail.id,
        },
        { preserveScroll: true }
    );

    popup.wishlist.success.show = true;

    setTimeout(() => {
        popup.wishlist.success.show = false;
    }, 1500);
};

let removeFromWishlist = () => {
    Inertia.post(
        "/wishlist/remove",
        {
            id: props.boardingHouseDetail.id,
        },
        { preserveScroll: true }
    );

    popup.wishlist.remove.show = true;

    setTimeout(() => {
        popup.wishlist.remove.show = false;
    }, 1500);
};

const restartRent = () => {
    buttonClicked.value = false;
};
</script>

<template>
    <Header />
    <Carousel
        :slides="props.images"
        controls
        indicators
        interval
        class="my-8"
    />
    <section class="mb-10 flex justify-center items-center">
        <div class="w-1/2">
            <div class="space-y-6">
                <div class="text-5xl font-semibold my-5 text-slate-800">
                    {{ props.boardingHouseDetail.boarding_name }}
                </div>
                <div
                    class="my-10 text-lg font-semibold flex justify-end items-center"
                >
                    <div
                        class="p-2 flex justify-center items-center space-x-1 hover:cursor-pointer hover:text-[#9b9b9b]"
                        @mouseover="mouseover = true"
                        @mouseleave="mouseover = false"
                        @click="
                            props.isWishlisted
                                ? removeFromWishlist()
                                : addToWishlist()
                        "
                        scroll-region
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            :fill="checkWishlisted()"
                            :stroke="strokeColor()"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"
                            ></path>
                        </svg>
                        <div>Wishlist</div>
                    </div>
                </div>
                <hr class="w-full h-0.5 bg-slate-100 border-0" />
                <div class="flex">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#000000"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="10" r="3" />
                        <path
                            d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"
                        />
                    </svg>
                    {{ props.boardingHouseDetail.address }}
                </div>
                <div class="flex justify-between">
                    <div class="w-2/3">
                        <div class="font-semibold">Facilities</div>
                        <div
                            v-for="facility in props.facilityList"
                            class="flex items-center space-x-2"
                        >
                            <img
                                :src="facility.facility_img_path"
                                class="w-5 h-5"
                                alt=""
                            />
                            <p>
                                {{ facility.facility_detail_name }}
                            </p>
                        </div>
                    </div>
                    <form
                        class="w-1/3 flex justify-start items-center shadow-lg p-2"
                        @submit.prevent="submit(boardingHouseDetail.id)"
                    >
                        <div
                            class="w-full flex flex-col items-center justify-center"
                        >
                            <div
                                v-if="isAvailable"
                                class="text-black font-semibold"
                            >
                                Capacity : {{ currVacancy }}
                            </div>
                            <div v-else class="text-red-500 font-semibold">
                                Capacity : {{ currVacancy }}
                            </div>
                            <div class="flex items-center">
                                <p class="text-xl font-semibold">
                                    IDR {{ props.boardingHouseDetail.price }}
                                </p>
                                <p>/month</p>
                            </div>

                            <div
                                v-if="$page.props.flash.message"
                                class="flex text-center text-red-500 text-sm font-bold px-4 py-3"
                                role="alert"
                            >
                                <p>{{ $page.props.flash.message }}</p>
                            </div>

                            <div class="border border-solid border-slate-300">
                                <label class="block">Start Date</label>
                                <input
                                    v-model="form.startDate"
                                    type="date"
                                    placeholder="date"
                                    @click="restartRent()"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                                />
                            </div>
                            <div
                                v-if="form.errors.startDate"
                                v-text="form.errors.startDate"
                                class="text-red-500 text-xs mt-1"
                            />
                            <div class="w-1/2 flex justify-end mt-5">
                                <button
                                    class="w-36 h-10 bg-indigo-900 flex justify-center items-center text-white rounded-md disabled:opacity-50"
                                    @click="handleClick(boardingHouseDetail.id)"
                                    :disabled="buttonClicked"
                                >
                                    Rent
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="flex justify-start">
                    <div
                        class="w-1/2 flex justify-between border-t-2 border-b-2 border-slate-100"
                    >
                        <div class="font-semibold">
                            Maintained By {{ props.ownerName }}
                        </div>
                        <img
                            :src="
                                props.ownerPicture
                                    ? props.ownerPicture
                                    : '../storage/images/CJ-GTASA.png'
                            "
                            alt="no image"
                            class="w-[5rem] h-[5rem] rounded-full object-scale-down"
                        />
                    </div>
                </div>
                <div v-if="props.boardingManagedBySameOwner">
                    <div class="font-semibold">Managed by Same Owner</div>
                    <div class="flex">
                        <div
                            v-for="boardingHouse in props.boardingManagedBySameOwner"
                            class="px-1 my-2 w-1/4 h-1/3"
                        >
                            <div class="bg-gray-200">
                                <img
                                    :src="boardingHouse.imageUrl"
                                    class="w-full h-80 object-cover"
                                    alt="Boarding Image"
                                />
                                <div class="h-2/3 space-y-1 p-2">
                                    <div class="font-semibold">
                                        {{ boardingHouse.boarding_name }}
                                    </div>
                                    <div class="text-xs text-gray-600 truncate">
                                        {{ boardingHouse.address }}
                                    </div>
                                    <div class="text-sm box-content truncate">
                                        {{ boardingHouse.boarding_desc }}
                                    </div>
                                    <Link
                                        href="/boarding/detail"
                                        :data="{ id: boardingHouse.id }"
                                        class="text-xs flex items-center font-semibold"
                                    >
                                        VIEW MORE DETAIL
                                        <div class="ml-1 text-sm">>></div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divide-y">
                    <div>
                        <div class="font-semibold text-xl">
                            Reviews
                            <div class="flex items-center space-x-20">
                                <svg
                                    aria-hidden="true"
                                    class="w-5 h-5 text-yellow-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                    ></path>
                                </svg>
                                {{
                                    props.averageRating
                                        ? props.averageRating
                                        : 0
                                }}
                                ({{ props.reviews.length }} review)
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center space-x-1">
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >5</span
                                >
                                <div
                                    class="w-1/6 h-2 bg-slate-200 rounded dark:bg-slate-200"
                                >
                                    <div
                                        class="h-2 bg-yellow-400 rounded"
                                        :style="{
                                            width:
                                                (
                                                    (props.ratingStar[4] /
                                                        props.totalReviewCount) *
                                                    100
                                                ).toFixed(2) == 'NaN'
                                                    ? 0
                                                    : (
                                                          (props.ratingStar[4] /
                                                              totalReviewCount) *
                                                          100
                                                      ).toFixed(2) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >{{
                                        (
                                            (props.ratingStar[4] /
                                                totalReviewCount) *
                                            100
                                        ).toFixed(2) == "NaN"
                                            ? 0
                                            : (
                                                  (props.ratingStar[4] /
                                                      totalReviewCount) *
                                                  100
                                              ).toFixed(2)
                                    }}%</span
                                >
                            </div>
                            <div class="flex items-center space-x-1">
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >4</span
                                >
                                <div
                                    class="w-1/6 h-2 bg-slate-200 rounded dark:bg-slate-200"
                                >
                                    <div
                                        class="h-2 bg-yellow-400 rounded"
                                        :style="{
                                            width:
                                                (
                                                    (props.ratingStar[3] /
                                                        totalReviewCount) *
                                                    100
                                                ).toFixed(2) == 'NaN'
                                                    ? 0
                                                    : (
                                                          (props.ratingStar[3] /
                                                              totalReviewCount) *
                                                          100
                                                      ).toFixed(2) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >{{
                                        (
                                            (props.ratingStar[3] /
                                                totalReviewCount) *
                                            100
                                        ).toFixed(2) == "NaN"
                                            ? 0
                                            : (
                                                  (props.ratingStar[3] /
                                                      totalReviewCount) *
                                                  100
                                              ).toFixed(2)
                                    }}%</span
                                >
                            </div>
                            <div class="flex items-center space-x-1">
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >3</span
                                >
                                <div
                                    class="w-1/6 h-2 bg-slate-200 rounded dark:bg-slate-200"
                                >
                                    <div
                                        class="h-2 bg-yellow-400 rounded"
                                        :style="{
                                            width:
                                                (
                                                    (props.ratingStar[2] /
                                                        totalReviewCount) *
                                                    100
                                                ).toFixed(2) == 'NaN'
                                                    ? 0
                                                    : (
                                                          (props.ratingStar[2] /
                                                              totalReviewCount) *
                                                          100
                                                      ).toFixed(2) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >{{
                                        (
                                            (props.ratingStar[2] /
                                                totalReviewCount) *
                                            100
                                        ).toFixed(2) == "NaN"
                                            ? 0
                                            : (
                                                  (props.ratingStar[2] /
                                                      totalReviewCount) *
                                                  100
                                              ).toFixed(2)
                                    }}%</span
                                >
                            </div>
                            <div class="flex items-center space-x-1">
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >2</span
                                >
                                <div
                                    class="w-1/6 h-2 bg-slate-200 rounded dark:bg-slate-200"
                                >
                                    <div
                                        class="h-2 bg-yellow-400 rounded"
                                        :style="{
                                            width:
                                                (
                                                    (props.ratingStar[1] /
                                                        totalReviewCount) *
                                                    100
                                                ).toFixed(2) == 'NaN'
                                                    ? 0
                                                    : (
                                                          (props.ratingStar[1] /
                                                              totalReviewCount) *
                                                          100
                                                      ).toFixed(2) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >{{
                                        (
                                            (props.ratingStar[1] /
                                                totalReviewCount) *
                                            100
                                        ).toFixed(2) == "NaN"
                                            ? 0
                                            : (
                                                  (props.ratingStar[1] /
                                                      totalReviewCount) *
                                                  100
                                              ).toFixed(2)
                                    }}%</span
                                >
                            </div>
                            <div class="flex items-center space-x-1">
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >1</span
                                >
                                <div
                                    class="w-1/6 h-2 bg-slate-200 rounded dark:bg-slate-200"
                                >
                                    <div
                                        class="h-2 bg-yellow-400 rounded"
                                        :style="{
                                            width:
                                                (
                                                    (props.ratingStar[0] /
                                                        totalReviewCount) *
                                                    100
                                                ).toFixed(1) == 'NaN'
                                                    ? 0
                                                    : (
                                                          (props.ratingStar[0] /
                                                              totalReviewCount) *
                                                          100
                                                      ).toFixed(1) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-500"
                                    >{{
                                        (
                                            (props.ratingStar[0] /
                                                totalReviewCount) *
                                            100
                                        ).toFixed(2) == "NaN"
                                            ? 0
                                            : (
                                                  (props.ratingStar[0] /
                                                      totalReviewCount) *
                                                  100
                                              ).toFixed(2)
                                    }}%</span
                                >
                            </div>
                        </div>
                    </div>
                    <div>
                        <div
                            class="flex justify-start space-x-2 items-start"
                            v-for="(review, key) in props.reviews"
                            :key="key"
                        >
                            <img
                                :src="
                                    review.user.profile_picture
                                        ? review.user.profile_picture
                                        : '../storage/images/CJ-GTASA.png'
                                "
                                alt="no image"
                                class="w-6 h-6 rounded-xl"
                            />
                            <div>
                                <div class="text-md font-semibold">
                                    {{ review.user.user_name }}
                                </div>
                                <div class="flex text-xs space-x-1">
                                    <div class="flex">
                                        {{ review.rating }}
                                        <svg
                                            aria-hidden="true"
                                            class="w-4 h-4 text-yellow-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div>
                                        {{
                                            new Date(
                                                review.updated_at
                                            ).toLocaleDateString()
                                        }}
                                    </div>
                                </div>
                                <div>{{ review.review_desc }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div
        v-if="popup.wishlist.remove.show"
        class="fixed inset-0 grid place-items-center"
        style="background: rgba(0, 0, 0, 0.4)"
        @click="popup.wishlist.remove.show = false"
    >
        <div class="bg-white w-1/4 rounded flex flex-col items-center">
            <div class="w-2/3 flex justify-center items-center pt-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#d0021b"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <header class="text-xl m-3">Removed from Wishlist!</header>
            </div>
            <div class="w-full flex justify-end px-6 pt-2 pb-3">
                <button
                    class="bg-blue-500 hover:bg-blue-600 rounded px-4 py-1 ml-3 text-white"
                    type="button"
                    @click="popup.wishlist.remove.show = false"
                >
                    Ok
                </button>
            </div>
        </div>
    </div>
    <div
        v-if="popup.wishlist.success.show"
        class="fixed inset-0 grid place-items-center"
        style="background: rgba(0, 0, 0, 0.4)"
        @click="popup.wishlist.success.show = false"
    >
        <div class="bg-white w-1/5 rounded flex flex-col items-center">
            <div class="w-2/3 flex justify-center items-center pt-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#7ed321"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <header class="text-xl m-3">Added to Wishlist!</header>
            </div>
            <div class="w-full flex justify-end px-6 pt-2 pb-3">
                <button
                    class="bg-blue-500 hover:bg-blue-600 rounded px-4 py-1 ml-3 text-white"
                    type="button"
                    @click="popup.wishlist.success.show = false"
                >
                    Ok
                </button>
            </div>
        </div>
    </div>
    <Footer />
</template>
