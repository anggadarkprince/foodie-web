@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.couriers.update', ['courier' => $courier->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Edit Courier</h1>
                <span class="text-gray-400">Food delivery man</span>
            </div>
            <div class="pt-2">
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="name" class="form-label">{{ __('Courier Name') }}</label>
                            <input id="name" type="text" class="form-input @error('name') border-red-500 @enderror"
                                   placeholder="Courier name" name="name" value="{{ old('name', $courier->name) }}">
                            @error('name') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror"
                                   placeholder="Email address" name="email" value="{{ old('email', $courier->email) }}">
                            @error('email') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Basic Information</h1>
                <span class="text-gray-400"> Courier profile info</span>
            </div>
            <div class="pt-2">
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="id_card" class="form-label">{{ __('ID Card Number') }}</label>
                            <input id="id_card" name="id_card" type="text" class="form-input @error('id_card') border-red-500 @enderror"
                                   placeholder="ID number" value="{{ old('id_card', $courier->id_card) }}">
                            @error('id_card') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="date_of_birth" class="form-label">{{ __('Date of Birth') }}</label>
                            <input id="date_of_birth" name="date_of_birth" type="text" class="form-input datepicker @error('date_of_birth') border-red-500 @enderror"
                                   placeholder="Date of birth" autocomplete="off" value="{{ old('date_of_birth', optional($courier->date_of_birth)->format('d F Y')) }}"
                                   data-min-date="{{ \Carbon\Carbon::now()->subYears(50)->format('Y-m-d') }}"
                                   data-max-date="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d')  }}"
                                   data-start-date="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d')  }}">
                            @error('date_of_birth') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="sm:flex -mx-2">
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="vehicle_type" class="form-label">{{ __('Vehicle Type') }}</label>
                            <input id="vehicle_type" name="vehicle_type" type="text" class="form-input @error('vehicle_type') border-red-500 @enderror"
                                   placeholder="Courier vehicle brand and type" value="{{ old('vehicle_type', $courier->vehicle_type) }}">
                            @error('vehicle_type') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="vehicle_plat" class="form-label">{{ __('Vehicle Plat') }}</label>
                            <input id="vehicle_plat" name="vehicle_plat" type="text" class="form-input @error('vehicle_plat') border-red-500 @enderror"
                                   placeholder="Vehicle plat police number" value="{{ old('vehicle_plat', $courier->vehicle_plat) }}">
                            @error('vehicle_plat') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap mb-3 sm:mb-4">
                    <label for="address" class="form-label">{{ __('Address') }}</label>
                    <textarea id="address" name="address" class="form-input @error('address') border-red-500 @enderror"
                              placeholder="Courier address">{{ old('address', $courier->address) }}</textarea>
                    @error('address') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-4">
                <h1 class="text-xl text-green-500">Courier Photo</h1>
                <span class="text-gray-400">Choose photo of courier</span>
            </div>
            <div class="sm:flex items-center pb-3 input-file-wrapper">
                <div class="bg-gray-400 inline-block mr-4 mb-3 sm:mb-0 rounded-md flex-shrink-0">
                    <img class="object-cover h-32 w-32 rounded-md" id="image-photo" src="{{ $courier->photo }}" alt="Photo">
                </div>
                <div class="flex w-full">
                    <input type="text" readonly class="form-input input-file-label rounded-tr-none rounded-br-none" placeholder="Select photo" aria-label="Avatar">
                    <div class="relative">
                        <input class="input-file button-primary absolute block hidden top-0" data-target-preview="#image-photo" type="file" name="photo" id="photo" accept="image/*">
                        <label for="photo" class="button-choose-file button-primary py-2 px-4 rounded-tl-none rounded-bl-none border border-transparent cursor-pointer">
                            Replace Photo
                        </label>
                    </div>
                </div>
            </div>
            @error('photo') <p class="form-text-error">{{ $message }}</p> @enderror
        </div>

        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Change Password</h1>
                <span class="text-gray-400">Leave it blank if keep old one</span>
            </div>
            <div class="pt-2">
                <div class="sm:flex -mx-2">
                    <div class="w-full px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="password" class="form-label">{{ __('Change Password') }}</label>
                            <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror"
                                   placeholder="New password" name="password" value="{{ old('password') }}">
                            @error('password') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="w-full px-2 sm:w-1/2">
                        <div class="flex flex-wrap mb-3 sm:mb-4">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                            <input id="password_confirmation" type="password" class="form-input @error('password_confirmation') border-red-500 @enderror"
                                   placeholder="Confirm the password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                            @error('password_confirmation') <p class="form-text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-primary button-sm">Save Courier</button>
        </div>
    </form>
@endsection
