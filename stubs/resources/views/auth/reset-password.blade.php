@extends('public._layout')

@section('main')
    @if ($errors->any())
        <div>
            <div>{{ __('Whoops! Something went wrong.') }}</div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
        	<label>{{ __('Email') }}</label>
        	<input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
        </div>

        <div>
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" required autocomplete="new-password" />
        </div>

        <div>
            <label>{{ __('Confirm password') }}</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div>
            <button type="submit">
                {{ __('Reset password') }}
            </button>
        </div>
    </form>
@endsection
