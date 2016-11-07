<template>
    <div>
        <div class="row middle-xs">
            <div class="col col-xs-6">
                <div class="form-group m-xs-b-4">
                    <label class="form-label">
                        Price
                    </label>
                    <span class="form-control-static">
                        ${{ priceInDollars }}
                    </span>
                </div>
            </div>
            <div class="col col-xs-6">
                <div class="form-group m-xs-b-4">
                    <label class="form-label">
                        Qty
                    </label>
                    <input v-model="quantity" class="form-control">
                </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary btn-block"
                @click="openStripe"
                :class="{ 'btn-loading': processing }"
                :disabled="processing"
            >
                Buy Tickets
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'price',
            'concertTitle',
            'concertId',
        ],
        data() {
            return {
                quantity: 1,
                stripeHandler: null,
                processing: false,
            }
        },
        computed: {
            description() {
                if (this.quantity > 1) {
                    return `${this.quantity} tickets to ${this.concertTitle}`
                }
                return `One ticket to ${this.concertTitle}`
            },
            totalPrice() {
                return this.quantity * this.price
            },
            priceInDollars() {
                return (this.price / 100).toFixed(2)
            },
            totalPriceInDollars() {
                return (this.totalPrice / 100).toFixed(2)
            },
        },
        methods: {
            initStripe() {
                const handler = StripeCheckout.configure({
                    key: App.stripePublicKey
                })

                window.addEventListener('popstate', () => {
                    handler.close()
                })

                return handler
            },
            openStripe(callback) {
                this.stripeHandler.open({
                    name: 'TicketBeast',
                    description: this.description,
                    currency: "usd",
                    allowRememberMe: false,
                    panelLabel: 'Pay {{amount}}',
                    amount: this.totalPrice,
                    image: '/img/checkout-icon.png',
                    token: this.purchaseTickets,
                })
            },
            purchaseTickets(token) {
                console.log({
                    email: token.email,
                    quantity: this.quantity,
                    payment_token: token.id,
                })

                // this.processing = true

                // axios.post(`/concerts/${this.concertId}/orders`, {
                //     email: token.email,
                //     quantity: this.quantity,
                //     payment_token: token.id,
                // }).then(response => {
                //     window.location.href = response.body.url
                // }).catch(response => {
                //     this.processing = false
                // })
            }
        },
        created() {
            this.stripeHandler = this.initStripe()
        }
    }
</script>
