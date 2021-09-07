<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <chat-room-selection v-if="curentRoom.id" :rooms="chatRooms" :curentRoom="curentRoom" v-on:roomchanged="setRoom($event)" />
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <message-container messages="messages" />
                    <input-message :room="curentRoom" v-on:messagesent="getMessages()"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import MessageContainer from './messageContainer.vue'
    import InputMessage from './inputMessage.vue'
import { Method } from '@inertiajs/inertia'
import axios from 'axios'
import ChatRoomSelection from './chatRoomSelection.vue'
    

    export default {
        components: {
            AppLayout,
            MessageContainer,
                InputMessage,
                ChatRoomSelection,     
        },
        data: function ()
        {
            return {
                chatRooms: [],
                curentRoom: [],
                messages: []
            }
        },
        watch:{
            curentRoom(val,oldVal) {
                if(oldVal.id){
                    this.disconnect(oldVal);
                }
                this.connect();
            }
        },
        methods:
        {
            connect()
            {
                if(this.curentRoom.id) {
                    let vm = this;
                    this.getMessages();
                    window.Echo.private("chat."+this.curentRoom.id)
                    .listen('.message.new',e => {
                        vm.getMessages();
                    });
                }
            },
            disconnect(room){
                window.Echo.leave("chat." + room.id);
            },
            getRooms()
            {
                axios.get('/chat/rooms')
                .then(response => {
                    this.chatRooms=response.data;
                    this.setRoom(response.data[0]);

                })
                .catch(error=>{
                    console.log(error);
                })
            },
            setRoom(room){
                this.curentRoom=room;
            },
            getMessages() 
            {
                axios.get('/chat/room/' + this.curentRoom.id + '/message')
                .then(response=>{
                    this.messages=response.data;
                })
                .catch(error=>{
                    console.log(error);
                })
            }
        },
        created()
        {
            this.getRooms();
        }
    }
</script>
