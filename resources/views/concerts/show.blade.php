@extends('layouts.master')

@section('body')
<div class="bg-soft p-xs-y-7 full-height">
    <div class="container">
        @if ($concert->hasPoster())
            @include('concerts.partials.card-with-poster', ['concert' => $concert])
        @else
            @include('concerts.partials.card-no-poster', ['concert' => $concert])
        @endif

        <div class="text-center text-dark-soft wt-medium">
            <p>Powered by TicketBeast</p>
        </div>
    </div>
</div>
@endsection

@push('beforeScripts')
<script src="https://checkout.stripe.com/checkout.js"></script>
@endpush
