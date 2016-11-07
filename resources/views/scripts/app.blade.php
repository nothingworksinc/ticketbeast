<script>
window.App = {
    csrfToken: '{{ csrf_token() }}',
    stripePublicKey: '{{ config('services.stripe.key') }}',
}
</script>
