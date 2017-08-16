@extends('layouts.backstage')

@section('backstageContent')
<div class="bg-light p-xs-y-4 border-b">
    <div class="container">
        <div class="flex-spaced flex-y-center">
            <h1 class="text-lg">
                <strong class="wt-medium">{{ $concert->title }}</strong>
                <span class="m-xs-x-2 text-dark-muted">/</span>
                <span class="wt-normal text-dark-soft text-base">
                    {{ $concert->formatted_date }}
                </span>
            </h1>
            <div class="text-base">
                <a href="{{ route('backstage.published-concert-orders.index', $concert) }}" class="wt-bold inline-block">
                    Orders
                </a>
            </div>
        </div>
    </div>
</div>
<div class="bg-soft p-xs-y-5">
    <div class="container m-xs-b-4">
        <div class="m-xs-b-6">
            <h2 class="m-xs-b-2 text-lg">Overview</h2>
            <div class="card">
                <div class="card-section border-b">
                    <p class="m-xs-b-4">This show is {{ $concert->percentSoldOut() }}% sold out.</p>
                    <progress class="progress" value="{{ $concert->ticketsSold() }}" max="{{ $concert->totalTickets() }}">{{ $concert->percentSoldOut() }}%</progress>
                </div>
                <div class="row">
                    <div class="col col-md-4 border-md-r">
                        <div class="card-section p-md-r-2 text-center text-md-left">
                            <h3 class="text-base wt-normal m-xs-b-1">Total Tickets Remaining</h3>
                            <div class="text-jumbo wt-bold">
                                {{ $concert->ticketsRemaining() }}
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-4 border-md-r">
                        <div class="card-section p-md-x-2 text-center text-md-left">
                            <h3 class="text-base wt-normal m-xs-b-1">Total Tickets Sold</h3>
                            <div class="text-jumbo wt-bold">
                                {{ $concert->ticketsSold() }}
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-4">
                        <div class="card-section p-md-l-2 text-center text-md-left">
                            <h3 class="text-base wt-normal m-xs-b-1">Total Revenue</h3>
                            <div class="text-jumbo wt-bold">
                                ${{ $concert->revenueInDollars() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h2 class="m-xs-b-2 text-lg">Recent Orders</h2>
            <div class="card">
                <div class="card-section">
                    @if($orders->isEmpty())
                    <div class="text-center">
                        No orders yet.
                    </div>
                    @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left">Email</th>
                                <th class="text-left">Tickets</th>
                                <th class="text-left">Amount</th>
                                <th class="text-left">Card</th>
                                <th class="text-left">Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->ticketQuantity() }}</td>
                                <td>${{ number_format($order->amount / 100, 2) }}</td>
                                <td><span class="text-dark-soft">****</span> {{ $order->card_last_four}}</td>
                                <td class="text-dark-soft">{{ $order->created_at->format('M j, Y @ g:ia') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

