@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">Restaurant</h1>
            <span class="text-gray-400">Manage all restaurants</span>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Name</p>
                    <p class="text-gray-600">{{ $restaurant->name }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Owner</p>
                    <p class="text-gray-600">{{ $restaurant->user->name }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Address</p>
                    <p class="text-gray-600">{{ $restaurant->address }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Image</p>
                    <div class="text-gray-600">
                        <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md">
                            <img class="object-cover h-20 w-20 rounded-md" src="{{ $restaurant->image }}" alt="{{ $restaurant->image }}">
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Location</p>
                    <p class="text-gray-600">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $restaurant->lat }},{{ $restaurant->lng }}" class="text-link" target="_blank">
                            Show in Map
                        </a>
                    </p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Balance</p>
                    <p class="text-gray-600">@numeric($restaurant->restaurant_balance)</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Created At</p>
                    <p class="text-gray-600">{{ $restaurant->created_at->format('d F Y H:i') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Updated At</p>
                    <p class="text-gray-600">{{ empty($restaurant->updated_at) ? '-' : $restaurant->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
