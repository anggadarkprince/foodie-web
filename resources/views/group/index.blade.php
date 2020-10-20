@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow px-6 py-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h1 class="text-xl text-green-500">User Group</h1>
                <span class="text-gray-400">Account group permission</span>
            </div>
            <div>
                <button class="button-blue button-sm modal-toggle" data-modal="#modal-filter">
                    <i class="mdi mdi-tune-vertical-variant"></i>
                </button>
                <a href="{{ url()->full() }}&export=1" class="button-blue button-sm text-center">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                @can('create', \App\Models\Group::class)
                    <a href="{{ route('admin.groups.create') }}" class="button-blue button-sm">
                        Create <i class="mdi mdi-plus-box-outline"></i>
                    </a>
                @endcan
            </div>
        </div>
        <table class="table-auto w-full mb-4">
            <thead>
            <tr>
                <th class="border-b border-t px-4 py-2 w-12">No</th>
                <th class="border-b border-t px-4 py-2 text-left">Group</th>
                <th class="border-b border-t px-4 py-2 text-left">Permission Total</th>
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
                    <td class="px-4 py-1">{{ $group->permission_total }}</td>
                    <td class="px-4 py-1">{{ $group->description ?: '-' }}</td>
                    <td class="px-4 py-1">{{ optional($group->created_at)->format('d F Y H:i') }}</td>
                    <td class="px-4 py-1 text-right">
                        <div class="dropdown">
                            <button class="dropdown-toggle button-primary button-sm">
                                Action <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('view', $group)
                                    <a href="{{ route('admin.groups.show', ['group' => $group->id]) }}" class="dropdown-item">
                                        <i class="mdi mdi-eye-outline mr-2"></i>View
                                    </a>
                                @endcan
                                @can('update', $group)
                                <a href="{{ route('admin.groups.edit', ['group' => $group->id]) }}" class="dropdown-item">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i>Edit
                                </a>
                                @endcan
                                @can('delete', $group)
                                    <hr class="border-gray-200 my-1">
                                    <button type="button" data-href="{{ route('admin.groups.destroy', ['group' => $group->id]) }}" data-label="{{ $group->group }}" class="dropdown-item confirm-delete">
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
        {{ $groups->withQueryString()->links() }}
    </div>

    @include('group.modal-filter')
    @include('partials.modal-delete')
@endsection
