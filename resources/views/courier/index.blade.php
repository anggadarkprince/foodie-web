@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl text-green-500">Couriers</h1>
                <span class="text-gray-400">Food delivery man</span>
            </div>
            <div>
                <button class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-tune-vertical-variant"></i>
                </button>
                <a href="{{ url()->full() }}&export=1" class="button-blue button-sm text-center">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                @can('create', \App\Models\Courier::class)
                    <a href="{{ route('admin.couriers.create') }}" class="button-blue button-sm">
                        Create <i class="mdi mdi-plus-box-outline"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @forelse ($couriers as $index => $courier)
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-2">
            <div class="flex flex-row items-center -mr-2">
                <div class="flex-shrink-0 inline-block mr-4 rounded-md">
                    <img class="object-cover rounded-md h-24 w-24" src="{{ $courier->photo }}" alt="{{ $courier->name }}">
                </div>
                <div class="sm:flex sm:flex-row sm:flex-grow sm:items-center sm:-mx-2">
                    <div class="flex flex-col truncate sm:px-2 sm:w-1/3">
                        <p class="text-green-500 text-lg">{{ $courier->name }}</p>
                        <p class="text-gray-500 text-sm truncate">{{ $courier->email ?: '-' }}</p>
                    </div>
                    <div class="flex flex-row sm:flex-col sm:px-2 sm:w-1/3">
                        <p class="mr-2">{{ $courier->vehicle_type }}</p>
                        <p class="text-gray-500">{{ $courier->vehicle_plat }}</p>
                    </div>
                    <div class="sm:px-2 sm:w-1/3">
                        IDR @numeric($courier->balance)
                    </div>
                </div>
                <div class="sm:px-2 sm:w-1/6 hidden sm:block">
                    @if(empty($courier->email_verified_at))
                        <span class="bg-red-500 text-white text-xs py-1 px-2 rounded">UNVERIFIED</span>
                    @else
                        <small class="bg-green-500 text-white text-xs py-1 px-2 rounded">VERIFIED</small>
                    @endif
                </div>
                <div class="sm:px-2 ml-auto sm:text-right">
                    <div class="dropdown">
                        <button class="dropdown-toggle button-primary button-sm">
                            Action <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('view', $courier)
                                <a href="{{ route('admin.couriers.show', ['courier' => $courier->id]) }}" class="dropdown-item">
                                    <i class="mdi mdi-eye-outline mr-2"></i>View
                                </a>
                            @endcan
                            @can('update', $courier)
                                <a href="{{ route('admin.couriers.edit', ['courier' => $courier->id]) }}" class="dropdown-item">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i>Edit
                                </a>
                            @endcan
                            @can('delete', $courier)
                                <hr class="border-gray-200 my-1">
                                <button type="button"
                                        data-href="{{ route('admin.couriers.destroy', ['courier' => $courier->id]) }}"
                                        data-label="{{ $courier->name }}" class="dropdown-item confirm-delete">
                                    <i class="mdi mdi-trash-can-outline mr-2"></i>Delete
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="sm:flex items-center bg-white rounded shadow-sm px-6 py-4 mb-2">
            No data available
        </div>
    @endforelse

    @if($couriers->lastPage() > 1)
        <div class="bg-white rounded shadow-sm px-6 py-4">
            {{ $couriers->withQueryString()->links() }}
        </div>
    @endif

    @include('courier.partials.modal-filter')
    @include('partials.modal-delete')
@endsection
