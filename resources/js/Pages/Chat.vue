<script setup>
import Pusher from "pusher-js";
import { onMounted, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Header from "../Shared/Header.vue";
import Footer from "../Shared/Footer.vue";
import axios from "axios";

const props = defineProps({
    contactDetails: Object,
});

const data = reactive({
    messages: [],
    newMessage: "",
    pusher: null,
    channel: null,
    receiverDetails: "",
    showChat: false,
    chatDestination: ""
});

const sendMessage = () => {
    Inertia.post("/chat", { id: data.chatDestination, message: data.newMessage });
    data.newMessage = "";
};

const getSelectedChat = (id) => {
    data.chatDestination = id;
    data.messages = [];
    data.showChat = true;
    axios.get('/chat/get', {
        params: {
            id: id,
        },
    })
    .then((response) => {
        let incomingData = response.data
        data.messages = incomingData.messages;
        data.receiverDetails = incomingData.receiverDetails;
    });
};

const initializeMessages = () => {
    data.pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
    });

    data.channel = data.pusher.subscribe("my-channel");
    data.channel.bind("my-event", (incomingData) => {
        data.messages.push(incomingData.chat);
    });
};

onMounted(() => {
    initializeMessages();
});
</script>

<template>
    <Header />
    <section class="flex justify-center items-center min-h-[60vh] m-[3rem]">
        <div
            class="w-[60rem] h-[40rem] flex justify-center border border-slate-100 rounded-lg shadow-md divide-x"
        >
            <div class="w-1/5 m-2">
                <div class="text-3xl text-gray-700 font-semibold">Chat</div>
                <div v-for="(contact, key) in props.contactDetails" :key="key">
                    <div
                        class="w-full h-[4rem] hover:bg-gray-200 hover:cursor-pointer flex items-center rounded-3xl space-x-2"
                        @click="getSelectedChat(contact.id)"
                    >
                        <div>
                            <img
                                :src="contact.profile_picture ? contact.profile_picture : '/storage/images/CJ-GTASA.png'"
                                alt="no image"
                                class="w-[3rem] h-[3rem] bg-white rounded-full object-scale-down"
                            />
                        </div>
                        <div>
                            {{ contact.user_name }}
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="data.showChat == true" class="w-4/5 p-[1rem]">
                <div class="h-1/6 flex items-center space-x-3">
                    <img
                        :src="data.receiverDetails.profile_picture ? data.receiverDetails.profile_picture : '/storage/images/CJ-GTASA.png'"
                        alt="no image"
                        class="w-[3rem] h-[3rem] bg-white rounded-full object-scale-down"
                    />
                    <p>
                        {{ data.receiverDetails.user_name }}
                    </p>
                </div>
                <div
                    v-if="data.messages.length >= 1"
                    class="h-4/6 overflow-auto scroll-auto space-y-2 flex flex-col-reverse"
                >
                    <div class="grow"></div>
                    <div
                        v-for="(message, key) in data.messages.slice().reverse()"
                        :key="key"
                        class="w-full space-y-2 flex items-end"
                        :class="{
                            'justify-end':
                                message.sender_id == $page.props.user.id,
                            'justify-start':
                                message.sender_id != $page.props.user.id,
                        }"
                    >
                        <div
                            class="w-fit p-2 border border-gray-200"
                            :class="{
                                'rounded-l-2xl bg-gray-200':
                                    message.sender_id == $page.props.user.id,
                                'rounded-r-2xl bg-white':
                                    message.sender_id != $page.props.user.id,
                            }"
                        >
                            {{ message.message }}
                        </div>
                    </div>
                </div>
                <div v-else class="h-4/6"></div>
                <div class="h-1/6 flex space-x-2 justify-center items-center">
                    <input
                        @keyup.enter="sendMessage()"
                        v-model="data.newMessage"
                        type="text"
                        placeholder="enter your message here"
                        class="w-full p-2 flex items-center border border-gray-200 rounded-full"
                    />
                    <form @submit.prevent="sendMessage()">
                        <button>Send</button>
                    </form>
                </div>
            </div>
            <div v-else class="w-4/5 p-[1rem]"></div>
        </div>
    </section>
    <Footer />
</template>
