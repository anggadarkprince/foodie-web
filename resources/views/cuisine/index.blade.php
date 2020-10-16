@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow px-6 py-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h1 class="text-xl">Cuisines</h1>
                <span class="text-gray-400">Manage all cuisines</span>
            </div>
            <div>
                <button class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-filter-variant"></i>
                </button>
                <a href="{{ url()->full() }}&export=1" class="button-blue button-sm text-center">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                @can('create', \App\Models\User::class)
                    <a href="{{ route('admin.cuisines.create') }}" class="button-blue button-sm">
                        Create <i class="mdi mdi-plus-box-outline"></i>
                    </a>
                @endcan
            </div>
        </div>
        <table class="table-auto w-full mb-4">
            <thead>
            <tr>
                <th class="border-b border-t px-4 py-2 w-12">No</th>
                <th class="border-b border-t px-4 py-2 text-left">Cuisine</th>
                <th class="border-b border-t px-4 py-2 text-left">Restaurant</th>
                <th class="border-b border-t px-4 py-2 text-left">Category</th>
                <th class="border-b border-t px-4 py-2 text-left">Price</th>
                <th class="border-b border-t px-4 py-2 text-right">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($cuisines as $index => $cuisine)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : '' }}">
                    <td class="px-4 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-1">
                        <div class="flex items-center my-1">
                            <div class="bg-gray-400 h-10 w-10 flex-shrink-0 inline-block mr-2 rounded-md">
                                <img class="object-cover h-10 w-10 rounded-md" src="{{ $cuisine->image }}" alt="{{ $cuisine->cuisine }}">
                            </div>
                            {{ $cuisine->cuisine }}
                        </div>
                    </td>
                    <td class="px-4 py-1">{{ $cuisine->restaurant->name }}</td>
                    <td class="px-4 py-1">{{ $cuisine->category->category }}</td>
                    <td class="px-4 py-1">@numeric($cuisine->price - $cuisine->discount)</td>
                    <td class="px-4 py-1 text-right">
                        <div class="dropdown">
                            <button class="dropdown-toggle button-primary button-sm">
                                Action <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('view', $cuisine)
                                    <a href="{{ route('admin.cuisines.show', ['cuisine' => $cuisine->id]) }}" class="dropdown-item">
                                        <i class="mdi mdi-eye-outline mr-2"></i>View
                                    </a>
                                @endcan
                                @can('update', $cuisine)
                                    <a href="{{ route('admin.cuisines.edit', ['cuisine' => $cuisine->id]) }}" class="dropdown-item">
                                        <i class="mdi mdi-square-edit-outline mr-2"></i>Edit
                                    </a>
                                @endcan
                                @can('delete', $cuisine)
                                    <hr class="border-gray-200 my-1">
                                    <button type="button" data-href="{{ route('admin.cuisines.destroy', ['cuisine' => $cuisine->id]) }}" data-label="{{ $cuisine->cuisine }}" class="dropdown-item confirm-delete">
                                        <i class="mdi mdi-trash-can-outline mr-2"></i>Delete
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No data available</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $cuisines->withQueryString()->links() }}
    </div>

    @include('cuisine.partials.modal-filter')
    @include('partials.modal-delete')
@endsection
