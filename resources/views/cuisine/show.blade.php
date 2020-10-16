@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">Cuisine</h1>
            <span class="text-gray-400">Manage all cuisines</span>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Cuisine</p>
                    <p class="text-gray-600">{{ $cuisine->cuisine }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Category</p>
                    <p class="text-gray-600">{{ $cuisine->category->category }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Restaurant</p>
                    <p class="text-gray-600">{{ $cuisine->restaurant->name }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Image</p>
                    <div class="text-gray-600">
                        <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md">
                            <img class="object-cover h-20 w-20 rounded-md" src="{{ $cuisine->image }}" alt="{{ $cuisine->image }}">
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Price</p>
                    <p class="text-gray-600">@numeric($cuisine->price)</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Discount</p>
                    <p class="text-gray-600">@numeric($cuisine->discount)</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Created At</p>
                    <p class="text-gray-600">{{ $cuisine->created_at->format('d F Y H:i') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Updated At</p>
                    <p class="text-gray-600">{{ empty($cuisine->updated_at) ? '-' : $cuisine->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
