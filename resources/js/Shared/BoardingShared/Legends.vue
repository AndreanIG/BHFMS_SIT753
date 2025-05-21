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

                <!-- Owner -->
                <div v-if="$page.props.user.role_id == 3">
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
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                />
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Update</span>
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                width="13"
                                height="13"
                            >
                                <path d="M2.5 2v6h6M21.5 22v-6h-6" />
                                <path
                                    d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"
                                />
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Reapprove</span>
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g
                                    id="SVGRepo_tracerCarrier"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g id="Navigation / House_Close">
                                        <path
                                            id="Vector"
                                            d="M10 15L12 13.0001M12 13.0001L14 11.0001M12 13.0001L10 11.0001M12 13.0001L14 15M4 16.8002V11.4522C4 10.9179 4 10.6506 4.06497 10.4019C4.12255 10.1816 4.2173 9.97307 4.34521 9.78464C4.48955 9.57201 4.69064 9.39569 5.09277 9.04383L9.89436 4.84244C10.6398 4.19014 11.0126 3.86397 11.4324 3.73982C11.8026 3.63035 12.1972 3.63035 12.5674 3.73982C12.9875 3.86406 13.3608 4.19054 14.1074 4.84383L18.9074 9.04383C19.3096 9.39569 19.5102 9.57201 19.6546 9.78464C19.7825 9.97307 19.8775 10.1816 19.9351 10.4019C20 10.6505 20 10.9184 20 11.4522V16.8037C20 17.9216 20 18.4811 19.7822 18.9086C19.5905 19.2849 19.2842 19.5906 18.9079 19.7823C18.4805 20.0001 17.9215 20.0001 16.8036 20.0001H7.19691C6.07899 20.0001 5.5192 20.0001 5.0918 19.7823C4.71547 19.5906 4.40973 19.2849 4.21799 18.9086C4 18.4807 4 17.9203 4 16.8002Z"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Disable</span>
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g
                                    id="SVGRepo_tracerCarrier"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g id="Navigation / House_Check">
                                        <path
                                            id="Vector"
                                            d="M15 11.0001L11 15.0001L9 13.0001M4 16.8002V11.4522C4 10.9179 4 10.6506 4.06497 10.4019C4.12255 10.1816 4.21779 9.97307 4.3457 9.78464C4.49004 9.57201 4.69064 9.39569 5.09277 9.04383L9.89436 4.84244C10.6398 4.19014 11.0126 3.86397 11.4324 3.73982C11.8026 3.63035 12.1972 3.63035 12.5674 3.73982C12.9875 3.86406 13.3608 4.19054 14.1074 4.84383L18.9074 9.04383C19.3096 9.39569 19.5102 9.57201 19.6546 9.78464C19.7825 9.97307 19.877 10.1816 19.9346 10.4019C19.9995 10.6506 20 10.9179 20 11.4522V16.8037C20 17.9216 20 18.4811 19.7822 18.9086C19.5905 19.2849 19.2837 19.5906 18.9074 19.7823C18.48 20.0001 17.921 20.0001 16.8031 20.0001H7.19691C6.07899 20.0001 5.5192 20.0001 5.0918 19.7823C4.71547 19.5906 4.40973 19.2849 4.21799 18.9086C4 18.4807 4 17.9203 4 16.8002Z"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Enable</span>
                    </li>
                </div>
                <div
                    v-if="
                        $page.props.user.role_id == 3 ||
                        $page.props.user.role_id == 4
                    "
                >
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 220.68 220.68"
                                xml:space="preserve"
                                class="fill-gray-600 hover:fill-purple-500"
                            >
                                <g
                                    id="SVGRepo_bgCarrier"
                                    stroke-width="0"
                                    stroke="currentColor"
                                ></g>
                                <g
                                    id="SVGRepo_tracerCarrier"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <path
                                            d="M67.869,65.313h39.476c4.143,0,7.5-3.358,7.5-7.5c0-4.142-3.357-7.5-7.5-7.5H67.869c-4.142,0-7.5,3.358-7.5,7.5 C60.369,61.954,63.727,65.313,67.869,65.313z"
                                        ></path>
                                        <path
                                            d="M142.81,81.994H67.869c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h74.941c4.143,0,7.5-3.358,7.5-7.5 C150.31,85.352,146.953,81.994,142.81,81.994z"
                                        ></path>
                                        <path
                                            d="M67.869,128.682h53.367c4.143,0,7.5-3.358,7.5-7.5c0-4.142-3.357-7.5-7.5-7.5H67.869c-4.142,0-7.5,3.358-7.5,7.5 C60.369,125.324,63.727,128.682,67.869,128.682z"
                                        ></path>
                                        <path
                                            d="M142.81,145.367H67.869c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h74.941c4.143,0,7.5-3.358,7.5-7.5 C150.31,148.725,146.953,145.367,142.81,145.367z"
                                        ></path>
                                        <path
                                            d="M174.423,18.144L158.477,2.197C157.071,0.79,155.163,0,153.174,0c-1.989,0-3.897,0.79-5.304,2.197L137.228,12.84 L126.585,2.197C125.178,0.79,123.27,0,121.281,0c-1.989,0-3.897,0.79-5.304,2.197l-10.641,10.642L94.697,2.197 C93.29,0.79,91.382,0,89.392,0c-1.989,0-3.897,0.79-5.304,2.198L73.451,12.839L62.809,2.197C61.403,0.79,59.495,0,57.506,0 s-3.897,0.79-5.304,2.197L36.257,18.144c-1.406,1.406-2.196,3.314-2.196,5.303v163.79c0,1.989,0.791,3.897,2.197,5.304 l15.945,15.942c2.928,2.929,7.676,2.928,10.605,0l10.644-10.642l10.645,10.642c1.407,1.406,3.315,2.196,5.304,2.196 c1.989,0,3.897-0.791,5.303-2.198l10.634-10.638l10.641,10.639c2.928,2.929,7.676,2.928,10.605,0l10.645-10.641l10.643,10.641 c1.464,1.464,3.384,2.196,5.303,2.196c1.919,0,3.839-0.732,5.303-2.196l15.945-15.942c1.407-1.406,2.197-3.314,2.197-5.304V23.447 C176.619,21.458,175.829,19.551,174.423,18.144z M161.619,184.13l-8.445,8.444l-10.643-10.641c-2.93-2.929-7.676-2.928-10.605,0 l-10.644,10.641l-10.642-10.641c-1.407-1.407-3.314-2.197-5.304-2.196c-1.989,0-3.897,0.79-5.303,2.198l-10.634,10.638 l-10.643-10.64c-2.929-2.928-7.676-2.928-10.605,0l-10.644,10.641l-8.445-8.444V26.554l8.445-8.447L68.149,28.75 c1.407,1.407,3.315,2.197,5.304,2.197c1.99,0,3.897-0.791,5.304-2.198l10.637-10.641l10.639,10.642 c1.406,1.407,3.314,2.197,5.304,2.197c1.99,0,3.897-0.79,5.304-2.197l10.641-10.643l10.644,10.643 c1.407,1.407,3.314,2.197,5.304,2.197c1.989,0,3.897-0.79,5.304-2.197l10.642-10.643l8.445,8.446V184.13z"
                                        ></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold"
                            >Add Payment to Boarding</span
                        >
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                class="w-6 h-6 fill-gray-600 hover:fill-purple-500"
                                stroke="currentColor"
                                viewBox="0 0 750 750"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g>
                                    <path
                                        d="m466.67 373.33c6.4414 0 11.664 5.2266 11.664 11.668v35h35c6.4453 0 11.668 5.2227 11.668 11.668 0 6.4414-5.2227 11.664-11.668 11.664h-46.664c-6.4453 0-11.668-5.2227-11.668-11.664v-46.668c0-6.4414 5.2227-11.668 11.668-11.668z"
                                    />
                                    <path
                                        d="m268.33 186.67c-6.4414 0-11.664 5.2227-11.664 11.664 0 6.4453 5.2227 11.668 11.664 11.668h140c6.4453 0 11.668-5.2227 11.668-11.668 0-6.4414-5.2227-11.664-11.668-11.664z"
                                    />
                                    <path
                                        d="m256.67 245c0-6.4414 5.2227-11.668 11.664-11.668h140c6.4453 0 11.668 5.2266 11.668 11.668s-5.2227 11.668-11.668 11.668h-140c-6.4414 0-11.664-5.2266-11.664-11.668z"
                                    />
                                    <path
                                        d="m268.33 280c-6.4414 0-11.664 5.2227-11.664 11.668 0 6.4414 5.2227 11.664 11.664 11.664h93.336c6.4414 0 11.664-5.2227 11.664-11.664 0-6.4453-5.2227-11.668-11.664-11.668z"
                                    />
                                    <path
                                        d="m226.46 454.32c-16.039-3.1914-28.129-17.348-28.129-34.324v-221.67h-23.332c-12.887 0-23.332-10.445-23.332-23.332v-35c0-2.418 0.24219-4.7773 0.71094-7.0547 3.2617-15.949 17.375-27.945 34.289-27.945h245c19.328 0 35 15.672 35 35v186.67c51.543 0 93.332 41.785 93.332 93.332s-41.789 93.332-93.332 93.332c-39.172 0-72.707-24.129-86.551-58.332h-146.79c-2.3516 0-4.6484-0.23047-6.8711-0.67578zm171.88-16.504 3.4102 8.4258c10.402 25.695 35.582 43.754 64.922 43.754 38.656 0 70-31.34 70-70s-31.344-70-70-70h-8.957l-8.5625 2.2031c-21.547 5.5469-39.152 21.164-47.402 41.551l-3.4102 8.4258-1.125 9.0391c-0.35938 2.8672-0.54297 5.7969-0.54297 8.7812s0.18359 5.9141 0.54297 8.7812zm-18.219-52.82c11-27.168 34.418-47.98 63.215-55.395v-189.61c0-6.4414-5.2227-11.668-11.664-11.668h-210v291.67c0 1.3867 0.23828 2.7148 0.68359 3.9492 1.6172 4.5 5.9219 7.7188 10.98 7.7188h4.457l13.66-27.32c5.9297-11.859 18.047-19.348 31.305-19.348zm-6.0625 46.668c-0.47656-3.8242-0.72266-7.7188-0.72266-11.668s0.24609-7.8438 0.72266-11.668h-91.301c-4.418 0-8.457 2.4961-10.434 6.4492l-8.4453 16.887zm-187.39-303.34c-6.4453 0-11.668 5.2227-11.668 11.668v35h23.332v-46.668z"
                                        fill-rule="evenodd"
                                    />
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold"
                            >Payment History</span
                        >
                    </li>
                </div>
                <!-- Tenant -->
                <div v-if="$page.props.user.role_id == 2">
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                stroke="currentColor"
                            >
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g
                                    id="SVGRepo_tracerCarrier"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M16.5 15V19.5H5.5V5.5H16.5V10M10 12.5H22.5"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    ></path>
                                    <path
                                        d="M20 10L22.5 12.5L20 15"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    ></path>
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">End Rent</span>
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                fill="#4b5563"
                                height="15px"
                                width="15px"
                                version="1.1"
                                id="Layer_1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 512 512"
                                xml:space="preserve"
                                stroke="currentColor"
                                class="hover:fill-purple-500"
                            >
                                <path
                                    d="M501.108,324.808c-18.078-22.785-51.248-26.095-73.455-7.46l-79.115,66.383h-37.109 c18.518-7.578,31.898-25.423,32.467-46.178c0.797-29.075-22.487-52.931-51.476-52.931H137.134v-15.706 c0-9.222-7.477-16.699-16.699-16.699H16.699C7.477,252.218,0,259.695,0,268.917v213.755c0,9.222,7.477,16.699,16.699,16.699 h103.737c9.222,0,16.699-7.477,16.699-16.699v-27.328l32.695,27.43c9.006,7.557,20.442,11.718,32.199,11.718h156.499 c11.758,0,23.194-4.162,32.201-11.719L493.3,396.711C514.768,378.695,518.272,346.441,501.108,324.808z M103.737,465.972H33.398 V285.615h70.339C103.737,296.354,103.737,458.287,103.737,465.972z M471.83,371.125l-102.571,86.063 c-3.002,2.519-6.814,3.906-10.734,3.906H202.027c-3.919,0-7.73-1.387-10.733-3.906l-54.161-45.44v-93.727h155.286 c10.134,0,18.373,8.324,18.092,18.619c-0.266,9.692-8.706,17.577-18.815,17.577c-7.262,0-59.115,0-65.689,0 c-9.222,0-16.699,7.477-16.699,16.699s7.477,16.699,16.699,16.699h29.59l25.868,21.703c6.005,5.037,13.628,7.813,21.467,7.813 h45.607c7.838,0,15.462-2.774,21.468-7.814l79.115-66.383c7.76-6.511,19.424-5.432,25.824,2.634 C480.972,353.162,479.575,364.628,471.83,371.125z"
                                ></path>
                                <path
                                    d="M368.08,92.437l-67.065-9.744l-29.992-60.772c-6.113-12.386-23.833-12.393-29.949,0l-29.992,60.772l-67.065,9.744 c-13.669,1.987-19.151,18.836-9.255,28.483l48.528,47.306l-11.457,66.794c-2.336,13.613,11.995,24.034,24.229,17.604 l59.986-31.536l59.984,31.535c12.157,6.392,26.58-3.898,24.229-17.604l-11.457-66.794l48.529-47.305 C387.225,111.279,381.757,94.424,368.08,92.437z M301.626,207.317c-54.351-28.574-36.583-28.69-91.155,0 c10.384-60.549,15.947-43.691-28.169-86.694c60.547-8.798,46.15,2.338,73.747-53.579c27.188,55.09,12.78,44.721,73.745,53.579 C285.814,163.494,291.21,146.586,301.626,207.317z"
                                ></path>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Make Review</span>
                    </li>
                </div>
                <!-- Admin -->
                <div v-if="$page.props.user.role_id == 1">
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
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                />
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Edit</span>
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g
                                    id="SVGRepo_tracerCarrier"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g id="Navigation / House_Close">
                                        <path
                                            id="Vector"
                                            d="M10 15L12 13.0001M12 13.0001L14 11.0001M12 13.0001L10 11.0001M12 13.0001L14 15M4 16.8002V11.4522C4 10.9179 4 10.6506 4.06497 10.4019C4.12255 10.1816 4.2173 9.97307 4.34521 9.78464C4.48955 9.57201 4.69064 9.39569 5.09277 9.04383L9.89436 4.84244C10.6398 4.19014 11.0126 3.86397 11.4324 3.73982C11.8026 3.63035 12.1972 3.63035 12.5674 3.73982C12.9875 3.86406 13.3608 4.19054 14.1074 4.84383L18.9074 9.04383C19.3096 9.39569 19.5102 9.57201 19.6546 9.78464C19.7825 9.97307 19.8775 10.1816 19.9351 10.4019C20 10.6505 20 10.9184 20 11.4522V16.8037C20 17.9216 20 18.4811 19.7822 18.9086C19.5905 19.2849 19.2842 19.5906 18.9079 19.7823C18.4805 20.0001 17.9215 20.0001 16.8036 20.0001H7.19691C6.07899 20.0001 5.5192 20.0001 5.0918 19.7823C4.71547 19.5906 4.40973 19.2849 4.21799 18.9086C4 18.4807 4 17.9203 4 16.8002Z"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold"
                            >Ban Boarding House</span
                        >
                    </li>
                    <li class="p-2 flex">
                        <div class="w-4 mr-2 transform">
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
                                <path
                                    d="M22 11.08V12a10 10 0 1 1-5.93-9.14"
                                ></path>
                                <polyline
                                    points="22 4 12 14.01 9 11.01"
                                ></polyline>
                            </svg>
                        </div>
                        <span class="mr-2 text-xs bold">Boarding Request</span>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</template>
