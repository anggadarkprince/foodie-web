@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">User</h1>
            <span class="text-gray-400">Manage all user account</span>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Name</p>
                    <p class="text-gray-600">{{ $user->name }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Email</p>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Verified At</p>
                    <p class="text-gray-600">{{ empty($user->email_verified_at) ? '-' : $user->email_verified_at->format('d F Y H:i:s') }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Avatar</p>
                    <p class="text-gray-600">
                        <div class="bg-gray-400 h-20 w-20 inline-block mr-2 rounded-md">
                            <img class="object-cover h-20 w-20 rounded-md" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        </div>
                    </p>
                </div>
            </div>
            <div>
                <div class="flex mb-2">
                    <p class="w-1/3">Type</p>
                    <p class="text-gray-600">{{ $user->type }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Last Logged In</p>
                    <p class="text-gray-600">{{ optional($user->last_logged_in)->format('d F Y H:i:s') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Created At</p>
                    <p class="text-gray-600">{{ optional($user->created_at)->format('d F Y H:i') ?: '-' }}</p>
                </div>
                <div class="flex mb-2">
                    <p class="w-1/3">Updated At</p>
                    <p class="text-gray-600">{{ optional($user->updated_at)->format('d F Y H:i') ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="mb-2">
            <h1 class="text-xl">Group Access</h1>
            <span class="text-gray-400">Choose what group user is owned</span>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach($user->groups as $group)
                <div class="mb-3">
                    <a href="{{ route('admin.groups.show', ['group' => $group->id]) }}" class="text-link">
                        <i class="mdi mdi-check-box-outline mr-1"></i>{{ $group->group }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
        <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
    </div>
@endsection
