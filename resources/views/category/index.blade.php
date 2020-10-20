@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl text-green-500">Category</h1>
                <span class="text-gray-400">Cuisine group category</span>
            </div>
            <div>
                <button class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-tune-vertical-variant"></i>
                </button>
                <a href="{{ url()->full() }}&export=1" class="button-blue button-sm text-center">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                @can('create', \App\Models\Category::class)
                    <a href="{{ route('admin.categories.create') }}" class="button-blue button-sm">
                        Create <i class="mdi mdi-plus-box-outline"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @forelse ($categories as $index => $category)
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-2">
            <div class="flex flex-row items-center -mx-2">
                <div class="px-2 sm:w-1/3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 inline-block mr-3 rounded-md">
                            <img class="object-cover h-10 w-10 rounded-md" src="{{ $category->icon }}" alt="{{ $category->category }}">
                        </div>
                        <p>{{ $category->category }}</p>
                    </div>
                </div>
                <div class="px-2 sm:w-1/3 hidden md:block">
                    {{ $category->description ?: '-' }}
                </div>
                <div class="px-2 sm:w-1/5 hidden md:block">
                    {{ $category->created_at->format('d M Y H:i') }}
                </div>
                <div class="px-2 ml-auto">
                    <div class="dropdown">
                        <button class="dropdown-toggle button-primary button-sm">
                            Action <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('view', $category)
                                <a href="{{ route('admin.categories.show', ['category' => $category->id]) }}" class="dropdown-item">
                                    <i class="mdi mdi-eye-outline mr-2"></i>View
                                </a>
                            @endcan
                            @can('update', $category)
                                <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}" class="dropdown-item">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i>Edit
                                </a>
                            @endcan
                            @can('delete', $category)
                                <hr class="border-gray-200 my-1">
                                <button type="button" data-href="{{ route('admin.categories.destroy', ['category' => $category->id]) }}" data-label="{{ $category->category }}" class="dropdown-item confirm-delete">
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

    @if($categories->lastPage() > 1)
        <div class="bg-white rounded shadow-sm px-6 py-4">
            {{ $categories->withQueryString()->links() }}
        </div>
    @endif

    @include('category.modal-filter')
    @include('partials.modal-delete')
@endsection
