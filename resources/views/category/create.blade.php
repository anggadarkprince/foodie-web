@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Create Category</h1>
                <span class="text-gray-400">Cuisine group category</span>
            </div>
            <div class="pt-2">
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <input id="category" type="text" class="form-input @error('category') border-red-500 @enderror"
                           placeholder="Category name" name="category" value="{{ old('category') }}">
                    @error('category') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" type="text" class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Group description" name="description">{{ old('description') }}</textarea>
                    @error('description') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Icon</h1>
                <span class="text-gray-400">Choose icon of category</span>
            </div>
            <div class="flex py-3 input-file-wrapper">
                <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select icon" aria-label="icon">
                <div class="relative">
                    <input class="input-file button-primary absolute block hidden top-0" type="file" name="icon" id="icon" accept="image/*">
                    <label for="icon" class="button-choose-file button-primary py-3 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                        Choose File
                    </label>
                </div>
            </div>
            @error('icon') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-primary button-sm">Save Category</button>
        </div>
    </form>
@endsection
