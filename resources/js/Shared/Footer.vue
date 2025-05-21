<script setup>
import { Link } from "@inertiajs/inertia-vue3";
</script>

<template>
    <footer class="">
        <section
            class="p-6 bg-indigo-900 flex justify-between mt-auto text-white"
        >
            <div class="ml-10">
                <p class="font-bold text-2xl">BHFMS</p>
                <p class="text-sm w-1/2 whitespace-wrap text-justify">
                    BHFMS or Boarding House Finder & Management System, is an
                    electronic marketplace that connects property owners and
                    renters of boarding houses. Our platform offers a variety of
                    features, including a user-friendly interface, easy renting
                    and payment processes, and a comprehensive management system
                    for property owners to manage their listings, complains, and
                    payments. Our mission is to simplify the renting process for
                    both parties and provide a reliable and secure platform for
                    the boarding house community.
                </p>
            </div>
            <div class="mr-36 w-1/6">
                <p class="font-bold text-2xl whitespace-nowrap">Page Link</p>
                <ul class="mt-6 space-y-3">
                    <li>
                        <Link href="/" class="whitespace-nowrap">Homepage</Link>
                    </li>
                    <li>
                        <Link href="/boarding/all" class="whitespace-nowrap"
                            >Boarding House List</Link
                        >
                    </li>
                </ul>
            </div>
        </section>
        <section class="bg-gray-200 text-center">&copy 2023 BHFMS</section>
    </footer>
</template>
