@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.categories.update', ['category' => $category->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl">Edit Category</h1>
                <span class="text-gray-400">Cuisine group category</span>
            </div>
            <div class="pt-2">
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <input id="category" type="text" class="form-input @error('category') border-red-500 @enderror"
                           placeholder="Category name" name="category" value="{{ old('category', $category->category) }}">
                    @error('category') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" name="description" class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Category description">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-4">
                <h1 class="text-xl">Icon</h1>
                <span class="text-gray-400">Choose icon of category</span>
            </div>
            <div class="flex items-center py-3 input-file-wrapper">
                <div class="bg-gray-400 inline-block mr-4 rounded-md flex-shrink-0">
                    <img class="object-cover h-20 w-20 rounded-md" id="image-icon" src="{{ $category->icon }}" alt="{{ $category->category }}">
                </div>
                <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select icon" aria-label="Icon">
                <div class="relative">
                    <input class="input-file button-primary absolute block hidden top-0" data-target-preview="#image-icon" type="file" name="icon" id="icon" accept="image/*">
                    <label for="icon" class="button-choose-file button-primary py-2 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                        Replace Icon
                    </label>
                </div>
            </div>
            @error('icon') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-orange button-sm">Update Category</button>
        </div>
    </form>
@endsection
