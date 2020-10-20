@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">Courier</h1>
            <span class="text-gray-400">Food delivery man</span>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Name</p>
                    <p class="text-gray-600">{{ $courier->name }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Email</p>
                    <p class="text-gray-600">{{ $courier->email }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Verified At</p>
                    <p class="text-gray-600">{{ empty($courier->email_verified_at) ? '-' : $courier->email_verified_at->format('d F Y H:i:s') }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Balance</p>
                    <p class="text-gray-600">@numeric($courier->balance)</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Photo</p>
                    <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md">
                        <img class="object-cover h-20 w-20 rounded-md" src="{{ $courier->photo }}" alt="{{ $courier->name }}">
                    </div>
                </div>
            </div>
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Vehicle Type</p>
                    <p class="text-gray-600">{{ $courier->vehicle_type }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Vehicle Plat</p>
                    <p class="text-gray-600">{{ $courier->vehicle_plat }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Date of Birth</p>
                    <p class="text-gray-600">{{ $courier->date_of_birth->format('d F Y') }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Address</p>
                    <p class="text-gray-600">{{ $courier->address }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Created At</p>
                    <p class="text-gray-600">{{ $courier->created_at->format('d F Y H:i') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Updated At</p>
                    <p class="text-gray-600">{{ empty($courier->updated_at) ? '-' : $courier->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
