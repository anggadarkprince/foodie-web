<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * AccountController constructor.
     *
     * @throws AuthorizationException
     */
    public function __construct()
    {
        $this->authorize('edit-account', User::class);
    }

    /**
     * Show account information form.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('account.index', ['user' => $request->user()]);
    }
}
