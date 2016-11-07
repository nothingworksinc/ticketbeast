import TicketCheckout from './components/TicketCheckout.vue'

require('./bootstrap')

const app = new Vue({
    components: {
        TicketCheckout,
    },
})

app.$mount('#app')
