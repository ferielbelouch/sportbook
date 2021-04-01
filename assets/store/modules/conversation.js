import Vue from 'vue';

export default
{
   state: {
    conversations: []
   },

   getters: {
    CONVERSATIONS: state =>  state.conversations,
    MESSAGES: state => conversationId => {
        return state.conversations.find(i => i.conversationId === conversationId).messages
    }
   },

   mutations: {
    SET_CONVERSATIONS: (state, payload) => {
        state.conversations = payload
    },
    SET_MESSAGES: (state, {conversationId, payload}) => {
        Vue.set(
            state.conversations.find(i => i.conversationId === conversationId),
            'messages',
            payload
        )
    },
    ADD_MESSAGE: (state, {conversationId, payload}) => {
            state.conversations.find(i => i.conversationId === conversationId).messages.push(payload)
    }
   },

   actions:
    {
        GET_CONVERSATIONS: ({commit}) =>
        {
            return fetch(`/conversations`)
                .then(result => result.json())
                .then((result) =>
                        {
                            commit("SET_CONVERSATIONS", result)
                        }
                    )
        },
        GET_MESSAGES: ({commit}, conversationId) =>
        {
            return fetch(`/messages/${conversationId}`)
                .then(result => result.json())
                .then((result) =>
                        {
                            commit("SET_MESSAGES", {conversationId, payload: result})
                        }
                    )
        },
        POST_MESSAGE: ({commit}, conversationId, content) =>
        {
            let formData = new FormData();
            formData.append('content', conversationId.content);
            return fetch(`/messages/${conversationId.conversationId}`, {
                method:"POST",
                body: formData
            })
                .then(result => result.json())
                .then((result) =>
                        {
                            commit("ADD_MESSAGE", {conversationId, payload: result})
                        }
                    )
        },
    }
}