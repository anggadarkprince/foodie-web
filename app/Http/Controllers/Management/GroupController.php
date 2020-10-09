<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveGroup;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class GroupController extends Controller
{
    /**
     * Display a listing of the group.
     */
    public function index()
    {
        $groups = Group::paginate();

        return view('group.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy([
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
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified group.
     *
     * @param \App\Models\Group $group
     * @return Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param \App\Models\Group $group
     * @return Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified group in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified group from storage.
     *
     * @param \App\Models\Group $group
     * @return Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
