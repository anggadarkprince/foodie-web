@extends('layouts.auth')

@section('content')
    <div class="sm:container sm:mx-auto sm:max-w-md sm:my-10">

        @if (session('status'))
            <div class="alert-green" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="bg-white sm:border-1 sm:rounded-md sm:shadow-lg">

            <header class="text-center text-2xl bg-gray-200 text-gray-700 py-5 px-6 sm:rounded-t-md">
                {{ __('Sign In') }}
            </header>

            <form class="px-5 space-y-4 sm:px-10" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="flex flex-wrap">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email"  class="form-input @error('email') border-red-500 @enderror"
                           placeholder="Your email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" placeholder="Password"
                           class="form-input @error('password') border-red-500 @enderror" name="password" required>
                    @error('password') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center py-2">
                    <label class="inline-flex items-center text-sm text-gray-700" for="remember">
                        <input type="checkbox" name="remember" id="remember" class="form-checkbox"
                            {{ old('remember') ? 'checked' : '' }}>
                        <span class="ml-2">{{ __('Remember Me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-link text-sm whitespace-no-wrap ml-auto"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <div class="flex flex-wrap">
                    <button type="submit" class="button-primary button-lg w-full">
                        {{ __('Login') }}
                    </button>

                    <p class="w-full block text-center text-gray-700 mt-6 mb-8 sm:text-sm">
                        @if (Route::has('register') && app_setting(\App\Models\Setting::MANAGEMENT_REGISTRATION))
                            {{ __("Don't have an account?") }}
                            <a class="text-link" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        @else
                            &copy {{ date('Y') }} Copyright all rights reserved
                        @endif
                    </p>
                </div>
            </form>

        </section>
    </div>
@endsection
