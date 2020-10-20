@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">Category</h1>
            <span class="text-gray-400">Cuisine group category</span>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Category</p>
                    <p class="text-gray-600">{{ $category->category }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Description</p>
                    <p class="text-gray-600">{{ $category->description ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Icon</p>
                    <div class="text-gray-600">
                        <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md">
                            <img class="object-cover h-20 w-20 rounded-md" src="{{ $category->icon }}" alt="{{ $category->icon }}">
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Created At</p>
                    <p class="text-gray-600">{{ $category->created_at->format('d F Y H:i') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Updated At</p>
                    <p class="text-gray-600">{{ empty($category->updated_at) ? '-' : $category->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
