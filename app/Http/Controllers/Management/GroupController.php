<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveGroup;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GroupController extends Controller
{

    /**
     * GroupController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Group::class);
    }

    /**
     * Display a listing of the group.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $groups = Group::withCount('permissions as permission_total')
            ->q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('group.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     *
     * @return View
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('feature')->get()->groupBy([
            'module',
            function ($item) {
                return $item['feature'];
            }
        ]);
        return view('group.create', compact('permissions'));
    }

    /**
     * Store a newly created group in storage.
     *
     * @param SaveGroup $request
     * @return RedirectResponse
     */
    public function store(SaveGroup $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $group = Group::create($request->except('permissions'));

                $group->permissions()->attach(
                    $request->input('permissions')
                );

                return redirect()->route('admin.groups.index')->with([
                    "status" => "success",
                    "message" => "Group {$group->group} successfully created"
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Create user group failed"
            ]);
        }
    }

    /**
     * Display the specified group.
     *
     * @param Group $group
     * @return View
     */
    public function show(Group $group)
    {
        $permissions = $group->permissions->groupBy([
            'module',
            function ($item) {
                return $item['feature'];
            }
        ]);
        return view('group.show', compact('group', 'permissions'));
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param Group $group
     * @return View
     */
    public function edit(Group $group)
    {
        $permissions = Permission::orderBy('module')->orderBy('feature')->get()->groupBy([
            'module',
            function ($item) {
                return $item['feature'];
            }
        ]);
        return view('group.edit', compact('group', 'permissions'));
    }

    /**
     * Update the specified group in storage.
     *
     * @param SaveGroup $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function update(SaveGroup $request, Group $group)
    {
        try {
            return DB::transaction(function () use ($request, $group) {
                $group->fill($request->except('permissions'));
                $group->save();

                $group->permissions()->sync(
                    $request->input('permissions')
                );

                return redirect()->route('admin.groups.index')->with([
                    "status" => "success",
                    "message" => "Group {$group->group} successfully updated"
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Update user group failed"
            ]);
        }
    }

    /**
     * Remove the specified group from storage.
     *
     * @param Group $group
     * @return RedirectResponse
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();
            return redirect()->route('admin.groups.index')->with([
                "status" => "warning",
                "message" => "Group {$group->group} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => "Delete user group failed"
            ]);
        }
    }
}
