<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\StoreUser;
use App\Http\Requests\Management\UpdateUser;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the user.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $users = User::with('groups')
            ->q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function create()
    {
        $groups = Group::all();

        return view('user.create', compact('groups'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUser $request
     * @return RedirectResponse
     */
    public function store(StoreUser $request)
    {
        try {
            $userInput = $request->except('avatar');

            $file = $request->file('avatar');
            if (!empty($file)) {
                $uploadPath = 'avatars/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $userInput['avatar'] = $path;
            }

            return DB::transaction(function () use ($userInput) {
                $user = User::create([
                    'name' => $userInput['name'],
                    'email' => $userInput['email'],
                    'password' => bcrypt($userInput['password']),
                    'avatar' => empty($userInput['avatar']) ? null : $userInput['avatar'],
                    'type' => User::TYPE_MANAGEMENT,
                ]);

                $user->groups()->attach($userInput['groups']);

                $user->sendEmailVerificationNotification();

                return redirect()->route('admin.users.index')->with([
                    "status" => "success",
                    "message" => "User {$user->name} successfully created"
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Create user failed"
            ]);
        }
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user)
    {
        $groups = Group::all();

        return view('user.edit', compact('user', 'groups'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUser $request, User $user)
    {
        try {
            return DB::transaction(function () use ($request, $user) {
                $request->whenFilled('password', function ($input) use ($request) {
                    $request->merge(['password' => bcrypt($input)]);
                });

                if ($request->isNotFilled('password')) {
                    $request->request->remove('password');
                }

                $userInput = $request->except(['avatar', 'groups', 'password_confirmation']);

                $file = $request->file('avatar');
                if (!empty($file)) {
                    $uploadPath = 'avatars/' . date('Ym');
                    $path = $file->storePublicly($uploadPath, 'public');
                    $userInput['avatar'] = $path;

                    // delete old file
                    if (!empty($user['avatar'])) {
                        Storage::disk('public')->delete($user['avatar']);
                    }
                }

                $user->fill($userInput);
                $user->save();

                $user->groups()->sync($request->groups);

                return redirect()->route('admin.users.index')->with([
                    "status" => "success",
                    "message" => "User {$user->name} successfully updated"
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Update user failed"
            ]);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('admin.users.index')->with([
                "status" => "warning",
                "message" => "User {$user->group} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => "Delete user failed"
            ]);
        }
    }
}
