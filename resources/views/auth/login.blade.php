@extends('layouts.master')

@section('body')
<div class="container-fluid bg-soft">
    <div class="full-height flex-center">
        <div class="constrain constrain-sm flex-fit">
            <form class="card p-xs-6" action="/login" method="POST">
                {{ csrf_field() }}
                <h1 class="text-xl wt-light text-center m-xs-b-6">Log in to your account</h1>
                <div class="form-group">
                    <label class="form-label pseudo-hidden">Email address</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            @icon('user', 'text-dark-muted text-xs')
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label pseudo-hidden">Password</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            @icon('lock-closed', 'text-dark-muted text-xs')
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                <button type="submit" class="btn btn-block btn-primary">Log in</button>
                @if($errors->any())
                <p class="text-center text-danger m-xs-t-2">
                    These credentials do not match our records.
                </p>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
