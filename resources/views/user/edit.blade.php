@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Edit User</h1>
                <span class="text-gray-400">Manage all user account</span>
            </div>
            <div class="pt-2">
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-input @error('name') border-red-500 @enderror"
                                   placeholder="User name" name="name" value="{{ old('name', $user->name) }}">
                            @error('name') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror"
                                   placeholder="Email address" name="email" value="{{ old('email', $user->email) }}">
                            @error('email') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="sm:flex -mx-2">
                    <div class="w-full px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                            <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror"
                                   placeholder="New password" name="password" value="{{ old('password') }}">
                            @error('password') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="w-full px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" type="password" class="form-input @error('password_confirmation') border-red-500 @enderror"
                                   placeholder="Confirm the password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                            @error('password_confirmation') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-4">
                <h1 class="text-xl text-green-500">Avatar</h1>
                <span class="text-gray-400">Choose photo of user</span>
            </div>
            <div class="sm:flex items-center pb-3 input-file-wrapper">
                <div class="bg-gray-400 inline-block mr-4 mb-3 sm:mb-0 rounded-md flex-shrink-0">
                    <img class="object-cover h-32 w-32 rounded-md" id="image-avatar" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                </div>
                <div class="flex w-full">
                    <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select avatar" aria-label="Avatar">
                    <div class="relative">
                        <input class="input-file button-primary absolute block hidden top-0" data-target-preview="#image-avatar" type="file" name="avatar" id="avatar" accept="image/*">
                        <label for="avatar" class="button-choose-file button-primary py-2 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                            Choose File
                        </label>
                    </div>
                </div>
            </div>
            @error('avatar') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Group Access</h1>
                <span class="text-gray-400">Choose what group user is owned</span>
            </div>
            <div class="pb-3">
                @error('groups') <p class="form-text-error">{{ $message }}</p> @enderror
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4 lg:grid-cols-4">
                    @foreach($groups as $group)
                        <label class="inline-flex items-center" for="group_{{ $group->id }}">
                            <input type="checkbox" name="groups[]" id="group_{{ $group->id }}" value="{{ $group->id }}" class="form-checkbox"
                                {{ in_array($group->id, old('groups', $user->groups->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $group->group }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-orange button-sm">Update User</button>
        </div>
    </form>
@endsection
