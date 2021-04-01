<!-- <template>
    <div class="mesgs">
        <template v-for="(message, index, key) in MESSAGES">
            <Message :message="message" />
        </template>
        <Input />
        </div>
</template> -->
<template>
    <div class="col-7 px-0">
        <div class="px-4 py-5 chat-box bg-white" ref="messagesBody">
            <template v-for="(message, index, key) in MESSAGES">
                <Message :message="message"/>
            </template>
        </div>
        <Input/>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import Message from './Message';
import Input from './Input';

export default {
    components: {Message, Input},
    computed:{
        MESSAGES () {
            return this.$store.getters.MESSAGES(this.$route.params.id);
        }
    },
       methods: {
            addMessage(data) {
                this.$store.commit("ADD_MESSAGE", {
                    conversationId: this.$route.params.id,
                    payload: data
                })
            }
        },
    mounted() {
        console.log(this.$route.params.id)
        this.$store.dispatch("GET_MESSAGES", this.$route.params.id)
            .then()
    }
}
</script>