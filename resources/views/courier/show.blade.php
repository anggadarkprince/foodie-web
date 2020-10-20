@extends('layouts.app')

@section('content')
    <div class="md:flex md:flex-row md:-mx-2">
        <div class="bg-white rounded shadow-sm p-6 mb-4 md:w-1/3 md:mx-2 self-start">
            <div class="text-center">
                <div class="inline-block mr-2 rounded-md">
                    <img class="object-cover h-40 w-40 rounded-full" src="{{ $courier->photo }}" alt="{{ $courier->name }}">
                </div>
                <h1 class="text-xl text-green-500">{{ $courier->name }}</h1>
                <p class="text-gray-500 mb-2 text-sm">{{ $courier->email }}</p>
                @if(empty($courier->email_verified_at))
                    <span class="bg-red-500 text-white text-xs py-1 px-2 rounded">UNVERIFIED</span>
                @else
                    <small class="bg-green-500 text-white text-xs py-1 px-2 rounded">VERIFIED</small>
                @endif
                <hr class="my-4">
                <div class="flex justify-around">
                    <div>
                        <p>Stars</p>
                        <h2 class="flex items-center text-xl sm:text-2xl text-orange-400">
                            {{ numeric($courier->orders()->avg('rating'), 1) }} <i class="mdi mdi-star"></i>
                        </h2>
                    </div>
                    <div>
                        <p>Orders</p>
                        <h2 class="text-xl sm:text-2xl text-green-500">
                            {{ $courier->orders()->count() }}x
                        </h2>
                    </div>
                    <div>
                        <p>Cancel</p>
                        <h2 class="text-xl sm:text-2xl text-green-500">
                            {{ $courier->orders()->status(\App\Models\Order::STATUS_CANCELED)->count() }}x
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:w-3/4 md:mx-2">
            <div class="bg-white rounded shadow-sm p-6 mb-4">
                <h3 class="text-green-500 text-lg mb-3">Account Profile</h3>
                <div class="divide-y divide-gray-200">
                    <div class="flex pb-2">
                        <p class="w-1/3">ID Card Number</p>
                        <p class="text-gray-600">{{ $courier->id_card ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Verified At</p>
                        <p class="text-gray-600">{{ optional($courier->email_verified_at)->format('d F Y') ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Date of Birth</p>
                        <p class="text-gray-600">{{ optional($courier->date_of_birth)->format('d F Y') ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Address</p>
                        <p class="text-gray-600">{{ $courier->address ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Created At</p>
                        <p class="text-gray-600">{{ optional($courier->created_at)->format('d F Y H:i') ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Updated At</p>
                        <p class="text-gray-600">{{ empty($courier->updated_at) ? '-' : $courier->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                <h3 class="text-green-500 text-lg mb-3 mt-3">Vehicle Profile</h3>
                <div class="divide-y divide-gray-200">
                    <div class="flex pb-2">
                        <p class="w-1/3">Vehicle Type</p>
                        <p class="text-gray-600">{{ $courier->vehicle_type ?: '-' }}</p>
                    </div>
                    <div class="flex py-2">
                        <p class="w-1/3">Vehicle Plat</p>
                        <p class="text-gray-600">{{ $courier->vehicle_plat ?: '-' }}</p>
                    </div>
                    <div class="flex pt-2">
                        <p class="w-1/3">Balance</p>
                        <p class="text-gray-600">@numeric($courier->balance)</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded shadow-sm p-6 mb-4">
                <h3 class="text-green-500 text-lg mb-3">Last Delivery</h3>
                <div class="divide-y divide-gray-200">
                    @foreach($lastDeliveries as $order)
                        <div class="flex items-center py-2">
                            <div class="flex flex-row items-center w-1/3">
                                <div class="flex-shrink-0 inline-block mr-2 rounded-md">
                                    <img class="object-cover h-10 w-10 rounded-md" src="{{ $order->restaurant->image }}" alt="{{ $order->restaurant->name }}">
                                </div>
                                <div>
                                    <p>{{ $order->restaurant->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $order->order_number }}</p>
                                </div>
                            </div>
                            <div class="w-1/4">{{ $order->user->name }}</div>
                            <div>{{ numeric($order->total_price) }}</div>
                            <div class="sm:ml-auto">{{ optional($order->created_at)->diffForHumans() ?: '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
