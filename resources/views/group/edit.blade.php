@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.groups.update', ['group' => $group->id]) }}" method="post">
        @csrf
        @method('put')
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Create Group</h1>
                <span class="text-gray-400">Account group permission</span>
            </div>
            <div class="py-2 space-y-4">
                <div class="flex flex-wrap">
                    <label for="group" class="form-label">{{ __('Group Name') }}</label>
                    <input id="group" type="text" class="form-input @error('group') border-red-500 @enderror"
                           placeholder="Group name" name="group" value="{{ old('group', $group->group) }}">
                    @error('group') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-wrap">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" type="text" class="form-input @error('description') border-red-500 @enderror"
                              placeholder="Group description" name="description">{{ old('description', $group->description) }}</textarea>
                    @error('description') <p class="form-text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4">
            <div class="mb-2">
                <h1 class="text-xl text-green-500">Permission Access</h1>
                <span class="text-gray-400">Choose what permission the group can do</span>
            </div>
            <div>
                @error('permissions') <p class="form-text-error">{{ $message }}</p> @enderror
                @foreach($permissions as $moduleName => $modules)
                    <h1 class="text-lg mt-4 mb-2 text-green-500">{{ ucwords(preg_replace('/(_|\-)/', ' ', $moduleName)) }}</h1>
                    @foreach($modules as $featureName => $features)
                        <h2 class="text-md pl-4 mb-1 text-gray-500">{{ ucwords(preg_replace('/(_|\-)/', ' ', $featureName)) }}</h2>
                        <div class="grid grid-cols-1 gap-4 pl-4 md:grid-cols-2 lg:grid-cols-4">
                            @foreach($features as $permission)
                                <div class="mb-3">
                                    <label class="inline-flex items-center" for="permission_{{ $permission->id }}">
                                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" class="form-checkbox"
                                            {{ in_array($permission->id, old('permissions', $group->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ ucwords(preg_replace('/(_|\-)/', ' ', $permission->permission)) }}</span>
                                    </label>
                                    <p class="text-sm text-gray-400 pl-5 leading-none">{{ $permission->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded shadow-sm px-6 py-4 mb-4 flex justify-between">
            <button type="button" onclick="history.back()" class="button-blue button-sm">Back</button>
            <button type="submit" class="button-orange button-sm">Update Group</button>
        </div>
    </form>
@endsection
