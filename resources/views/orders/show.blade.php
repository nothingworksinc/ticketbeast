@extends('layouts.master')

@section('body')
<div class="bg-soft p-xs-y-7 full-height">
    <div class="container">
        <div class="constrain-xl m-xs-auto">
            <div class="m-xs-b-6">
                <div class="flex-baseline flex-spaced p-xs-y-4 border-b">
                    <h1 class="text-xl">Order Summary</h1>
                    <a href="{{ url("/orders/{$order->confirmation_number}") }}" class="link-brand-soft">{{ $order->confirmation_number }}</a>
                </div>
                <div class="p-xs-y-4 border-b">
                    <p>
                        <strong>Order Total: ${{ number_format($order->amount / 100, 2) }}</strong>
                    </p>
                    <p class="text-dark-soft">Billed to Card #: **** **** **** {{ $order->card_last_four }}</p>
                </div>
            </div>
            <div class="m-xs-b-7">
                <h2 class="text-lg wt-normal m-xs-b-4">Your Tickets</h2>

                @foreach($order->tickets as $ticket)
                <div class="card m-xs-b-5">
                    <div class="card-section p-xs-y-3 flex-baseline flex-spaced text-light bg-gray">
                        <div>
                            <h1 class="text-xl wt-normal">{{ $ticket->concert->title }}</h1>
                            <p class="text-light-muted">{{ $ticket->concert->subtitle }}</p>
                        </div>
                        <div class="text-right">
                            <strong>General Admission</strong>
                            <p class="text-light-soft">Admit one</p>
                        </div>
                    </div>
                    <div class="card-section border-b">
                        <div class="row">
                            <div class="col-sm">
                                <div class="media-object">
                                    <div class="media-left">
                                        @icon('calendar', 'text-brand-muted')
                                    </div>
                                    <div class="media-body p-xs-l-4">
                                        <p class="wt-bold">
                                            <time datetime="{{ $ticket->concert->date->format('Y-m-d H:i') }}">
                                                {{ $ticket->concert->date->format('l, F jS, Y') }}
                                            </time>
                                        </p>
                                        <p class="text-dark-soft">
                                            Doors at {{ $ticket->concert->date->format('g:ia') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="media-object">
                                    <div class="media-left">
                                        @icon('location', 'text-brand-muted')
                                    </div>
                                    <div class="media-body p-xs-l-4">
                                        <p class="wt-bold">{{ $ticket->concert->venue }}</p>
                                        <div class="text-dark-soft">
                                            <p>{{ $ticket->concert->venue_address }}</p>
                                            <p>
                                                {{ $ticket->concert->city }}, {{ $ticket->concert->state }} {{ $ticket->concert->zip }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-section flex-baseline flex-spaced">
                        <p class="text-lg">{{ $ticket->code }}</p>
                        <p>{{ $order->email }}</p>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="text-center text-dark-soft wt-medium">
                <p>Powered by TicketBeast</p>
            </div>
        </div>
    </div>
</div>
@endsection
