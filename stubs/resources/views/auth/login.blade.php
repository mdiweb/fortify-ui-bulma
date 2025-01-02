@extends('public._layout')

@section('main')
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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus />
            </div>

            <div>
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" required autocomplete="current-password" />
            </div>

            <div>
                <label for="remember">{{ __('Remember me') }}</label>
                <input type="checkbox" name="remember" id="remember">
            </div>

            <div>
                <label>&nbsp;</label>
                <button type="submit">
                   {{ __('Login') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="forgot">
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            @endif
        </form>
@endsection
