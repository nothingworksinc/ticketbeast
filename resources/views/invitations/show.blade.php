@extends('layouts.master')

@section('body')
<div class="container-fluid bg-soft">
    <div class="full-height flex-center">
        <div class="constrain constrain-sm flex-fit">
            <form class="card p-xs-6" action="/register" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="invitation_code" value="{{ $invitation->code }}">
                <h1 class="text-xl wt-light text-center m-xs-b-6">Join TicketBeast</h1>
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <label class="form-label pseudo-hidden">Email address</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            @icon('user', 'text-dark-muted text-xs')
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}">
                    </div>
                    @if ($errors->has('email'))
                    <p class="text-danger m-xs-t-2">{{ $errors->first('email') }}</p>
                    @endif
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <label class="form-label pseudo-hidden">Password</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            @icon('lock-closed', 'text-dark-muted text-xs')
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    @if ($errors->has('password'))
                    <p class="text-danger m-xs-t-2">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <button type="submit" class="btn btn-block btn-primary">Create Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
