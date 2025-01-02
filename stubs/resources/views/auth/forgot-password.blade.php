@extends('public._layout')

@section('main')
    <div style="max-width: 800px; margin-top: 40px;">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('password.email') }}" style="margin-top">
        @csrf

        <div>
            <label>{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus />
            <button type="submit" style="margin-left: 2em;">
                {{ __('Email password reset link') }}
            </button>
        </div>

        <div class="forgot">
            <a href="{{ route('login') }}">
                {{ __('Return to login page.') }}
            </a>
        </div>
    </form>
@endsection
