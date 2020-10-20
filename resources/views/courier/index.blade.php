@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow px-6 py-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h1 class="text-xl">Couriers</h1>
                <span class="text-gray-400">Food delivery man</span>
            </div>
            <div>
                <button class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-filter-variant"></i>
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
        <table class="table-auto w-full mb-4">
            <thead>
            <tr>
                <th class="border-b border-t px-4 py-2 w-12">No</th>
                <th class="border-b border-t px-4 py-2 text-left">Courier</th>
                <th class="border-b border-t px-4 py-2 text-left">Email</th>
                <th class="border-b border-t px-4 py-2 text-left">Vehicle</th>
                <th class="border-b border-t px-4 py-2 text-right">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($couriers as $index => $courier)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : '' }}">
                    <td class="px-4 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-1">
                        <div class="flex items-center my-1">
                            <div class="h-10 w-10 inline-block mr-2 rounded-md">
                                <img class="object-cover h-10 w-10 rounded-md" src="{{ $courier->photo }}" alt="{{ $courier->name }}">
                            </div>
                            {{ $courier->name }}
                        </div>
                    </td>
                    <td class="px-4 py-1">{{ $courier->email ?: '-' }}</td>
                    <td class="px-4 py-1">
                        <p>{{ $courier->vehicle_type }}</p>
                        <small class="text-gray-500">{{ $courier->vehicle_plat }}</small>
                    </td>
                    <td class="px-4 py-1 text-right">
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
                                    <button type="button" data-href="{{ route('admin.couriers.destroy', ['courier' => $courier->id]) }}" data-label="{{ $courier->name }}" class="dropdown-item confirm-delete">
                                        <i class="mdi mdi-trash-can-outline mr-2"></i>Delete
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No data available</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $couriers->withQueryString()->links() }}
    </div>

    @include('courier.partials.modal-filter')
    @include('partials.modal-delete')
@endsection
