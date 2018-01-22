@extends('layouts.master')

@section('body')
<div class="container-fluid bg-soft">
    <div class="full-height flex-center">
        <div class="constrain constrain-sm flex-fit">
            <div class="card p-xs-6">
                <h1 class="text-xl wt-light text-center m-xs-b-6">Connect your Stripe account</h1>
                <p class="m-xs-b-6">
                    Good news, TicketBeast now integrates directly with your Stripe account!
                </p>
                <p class="m-xs-b-6">
                    To continue, connect your Stripe account by clicking the button below:
                </p>
                <div>
                    <a href="{{ route('backstage.stripe-connect.authorize') }}" class="btn btn-block btn-primary">Connect with Stripe</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
