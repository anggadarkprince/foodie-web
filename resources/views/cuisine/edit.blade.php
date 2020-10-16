@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.cuisines.update', ['cuisine' => $cuisine->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Create Cuisine</h1>
                <span class="text-gray-400">Manage all cuisines</span>
            </div>
            <div class="pt-2">
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="cuisine" class="form-label">{{ __('Cuisine Name') }}</label>
                    <input id="cuisine" type="text" class="form-input @error('cuisine') border-red-500 @enderror"
                           placeholder="Cuisine name" name="cuisine" value="{{ old('cuisine', $cuisine->cuisine) }}">
                    @error('cuisine') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="category_id" class="form-label">{{ __('Category') }}</label>
                            <div class="relative w-full">
                                <select class="form-input pr-8 @error('category_id') border-red-500 @enderror" name="category_id" id="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"{{ old('category_id', $cuisine->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->category }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                            @error('category_id') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="restaurant_id" class="form-label">{{ __('Restaurant') }}</label>
                            <div class="relative w-full">
                                <select class="form-input pr-8 @error('restaurant_id') border-red-500 @enderror" name="restaurant_id" id="restaurant_id">
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}"{{ old('restaurant_id', $cuisine->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                            @error('restaurant_id') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" type="text" class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Cuisine description" name="description">{{ old('description', $cuisine->description) }}</textarea>
                    @error('description') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="price" class="form-label">{{ __('Price') }}</label>
                            <input id="price" type="text" class="form-input input-numeric @error('price') border-red-500 @enderror"
                                   placeholder="Price" name="price" value="{{ old('price', numeric($cuisine->price)) }}">
                            @error('price') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="discount" class="form-label">{{ __('Discount') }}</label>
                            <input id="discount" type="text" class="form-input input-numeric @error('discount') border-red-500 @enderror"
                                   placeholder="Discount" name="discount" value="{{ old('discount', numeric($cuisine->discount)) }}">
                            @error('discount') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Image</h1>
                <span class="text-gray-400">Choose photo of food</span>
            </div>
            <div class="flex items-center pb-3 input-file-wrapper">
                <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md flex-shrink-0">
                    <img class="object-cover h-20 w-20 rounded-md" src="{{ $cuisine->image }}" alt="{{ $cuisine->cuisine }}">
                </div>
                <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select image" aria-label="Image">
                <div class="relative">
                    <input class="input-file button-primary absolute block hidden top-0" type="file" name="image" id="image" accept="image/*">
                    <label for="image" class="button-choose-file button-primary py-3 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                        Replace Image
                    </label>
                </div>
            </div>
            @error('image') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-orange button-sm">Update Cuisine</button>
        </div>
    </form>
@endsection
