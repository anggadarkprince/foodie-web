@extends('layouts.app')

@section('content')
    <div class="sm:container sm:mx-auto sm:max-w-md sm:my-10">
        @if (session('status'))
            <div class="alert-green" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="bg-white sm:border-1 sm:rounded-md sm:shadow-lg">
            <header class="text-center text-2xl bg-gray-200 text-gray-700 py-5 px-6 sm:rounded-t-md">
                {{ __('Account') }}
            </header>

            <form class="px-5 space-y-4 sm:px-10" method="POST" action="{{ route('user-profile-information.update') }}">
                @csrf
                @method('put')

                <div class="flex flex-wrap">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-input @error('name') border-red-500 @enderror"
                           name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Your full name">
                    <p class="form-text-error">{{ $errors->updateProfileInformation->first('name') }}</p>
                </div>

                <div class="flex flex-wrap">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror" name="email"
                           value="{{ old('email', $user->email) }}" required autocomplete="email" placeholder="Email address">
                    <p class="form-text-error">{{ $errors->updateProfileInformation->first('email') }}</p>
                </div>

                <div class="pt-3 pb-8">
                    <button type="submit" class="button-primary button-lg w-full">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>

        </section>
    </div>
@endsection
