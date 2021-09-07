<template>
    <div class="grid grid-cols-2">
        <div class="font-bold text-x1">
            {{ selected.name }} Chat
        </div>
        <select v-model="selected" @change="$emit('roomchanged',selected)" class="float-right">
            <option v-for="(room,index) in room" :key="index" value="room">
                {{ room.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props:['rooms','curentRoom'],
    data: function(){
        return{
            selected:''
        }
    },
    created(){
        this.selected=this.curentRoom;
    }
}
</script>