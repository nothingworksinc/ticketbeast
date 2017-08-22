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
                <a href="{{ route('backstage.published-concert-orders.index', $concert) }}" class="inline-block m-xs-r-4">
                    Orders
                </a>
                <a href="{{ route('backstage.concert-messages.new', $concert) }}" class="wt-bold inline-block">
                    Message Attendees
                </a>
            </div>
        </div>
    </div>
</div>
<div class="bg-soft p-xs-y-5">
    <div class="container m-xs-b-4">
        <div class="constrain constrain-lg m-xs-auto">
            <h1 class="text-xl wt-light text-center m-xs-b-4 text-dark">New Message</h1>

            @if (session()->has('flash'))
            <div class="alert alert-success m-xs-b-4">
                {{ session('flash') }}
            </div>
            @endif

            <div class="card p-xs-6">
                <form action="{{ route('backstage.concert-messages.store', $concert) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group {{ $errors->first('subject', 'has-error')}}">
                        <label class="form-label">Subject</label>
                        <input name="subject" class="form-control" value="{{ old('subject') }}">
                        @if($errors->has('subject'))
                        <p class="help-block">
                            {{ $errors->first('subject') }}
                        </p>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->first('message', 'has-error')}}">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="10">{{ old('message') }}</textarea>
                        @if($errors->has('message'))
                        <p class="help-block">
                            {{ $errors->first('message') }}
                        </p>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-primary btn-block text-smooth">Send Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

