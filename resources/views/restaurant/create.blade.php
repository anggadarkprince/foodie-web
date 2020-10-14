@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.restaurants.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Create Restaurant</h1>
                <span class="text-gray-400">Manage all restaurants</span>
            </div>
            <div class="pt-2">
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-input @error('name') border-red-500 @enderror"
                                   placeholder="Restaurant name" name="name" value="{{ old('name') }}">
                            @error('name') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="user_id" class="form-label">{{ __('Owner') }}</label>
                            <div class="relative w-full">
                                <select class="form-input pr-8 @error('user_id') border-red-500 @enderror" name="user_id" id="user_id">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"{{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                            @error('user_id') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="address" class="form-label">{{ __('Address') }}</label>
                    <textarea id="address" type="text" class="form-input @error('address') border-red-500 @enderror"
                              placeholder="Restaurant address" name="address">{{ old('address') }}</textarea>
                    @error('address') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Image</h1>
                <span class="text-gray-400">Choose photo of restaurant</span>
            </div>
            <div class="flex pb-3 input-file-wrapper">
                <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select avatar" aria-label="Image">
                <div class="relative">
                    <input class="input-file button-primary absolute block hidden top-0" type="file" name="image" id="image" accept="image/*">
                    <label for="image" class="button-choose-file button-primary py-3 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                        Choose Image
                    </label>
                </div>
            </div>
            @error('image') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-primary button-sm">Save Restaurant</button>
        </div>
    </form>
@endsection
