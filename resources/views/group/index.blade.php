@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow px-6 py-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h1 class="text-xl">User Group</h1>
                <span class="text-gray-400">Account group permission</span>
            </div>
            <div>
                <a href="#modal-filter" class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="{{ url()->current() }}&export=1" class="button-blue button-sm text-center">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <a href="{{ route('admin.groups.create') }}" class="button-blue button-sm">
                    Create <i class="mdi mdi-plus-box-outline"></i>
                </a>
            </div>
        </div>
        <table class="table-auto w-full mb-4">
            <thead>
            <tr>
                <th class="border-b border-t px-4 py-2 w-12">No</th>
                <th class="border-b border-t px-4 py-2 text-left">Group</th>
                <th class="border-b border-t px-4 py-2 text-left">Description</th>
                <th class="border-b border-t px-4 py-2 text-left">Created At</th>
                <th class="border-b border-t px-4 py-2 text-right">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($groups as $index => $group)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : '' }}">
                    <td class="px-4 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-1">{{ $group->group }}</td>
                    <td class="px-4 py-1">{{ $group->description ?: '-' }}</td>
                    <td class="px-4 py-1">{{ $group->created_at->format('d F Y H:i') }}</td>
                    <td class="px-4 py-1 text-right">
                        <div class="dropdown">
                            <button class="dropdown-toggle button-primary button-sm">
                                Action <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item">
                                    <i class="mdi mdi-eye-outline mr-2"></i>View
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i>Edit
                                </a>
                                <hr class="border-gray-200 my-1">
                                <a href="#" class="dropdown-item">
                                    <i class="mdi mdi-trash-can-outline mr-2"></i>Delete
                                </a>
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
        {{ $groups->withQueryString()->links() }}
    </div>

    <div id="modal-filter" class="modal">
        <div class="modal-content">
            <div class="border-b border-gray-200 pb-3">
                <span class="close dismiss-modal">&times;</span>
                <h3 class="text-xl">Filters</h3>
            </div>
            <form action="{{ url()->full() }}" method="get" class="py-3 space-y-4">
                <div class="flex flex-wrap">
                    <label for="q" class="form-label">{{ __('Search') }}</label>
                    <input id="q" type="search" class="form-input"
                           placeholder="Search data" name="q" value="{{ request()->get('q') }}">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-wrap">
                        <label for="date_from" class="form-label">{{ __('Date From') }}</label>
                        <input id="date_from" type="search" class="form-input"
                               placeholder="Date created since" name="date_from" value="{{ request()->get('date_from') }}">
                    </div>
                    <div class="flex flex-wrap">
                        <label for="date_to" class="form-label">{{ __('Date To') }}</label>
                        <input id="date_to" type="search" class="form-input"
                               placeholder="Date created until" name="date_to" value="{{ request()->get('date_to') }}">
                    </div>
                </div>
                <div class="text-right pt-4">
                    <button type="button" class="button-blue button-sm dismiss-modal">Close</button>
                    <button type="submit" class="button-primary button-sm">Apply</button>
                </div>
            </form>
        </div>
    </div>
@endsection
