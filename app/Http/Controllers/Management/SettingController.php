<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Setting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class SettingController extends Controller
{
    /**
     * SettingController constructor.
     *
     * @throws AuthorizationException
     */
    public function __construct()
    {
        $this->authorize('edit-setting', Setting::class);
    }

    /**
     * Show setting form.
     *
     * @return View
     */
    public function index()
    {
        $groups = Group::all();

        return view('setting.index', compact('groups'));
    }

    /**
     * Update setting controller.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $rules = [
            Setting::APP_TITLE => ['required'],
            Setting::APP_TAGLINE => ['required'],
            Setting::APP_DESCRIPTION => ['required'],
            Setting::APP_KEYWORDS => ['required'],
            Setting::APP_LANGUAGE => ['required'],
            Setting::MANAGEMENT_REGISTRATION => ['nullable'],
            Setting::DEFAULT_MANAGEMENT_GROUP => ['nullable'],
            Setting::EMAIL_SUPPORT => ['required', 'email'],
            Setting::EMAIL_BUG_REPORT => ['required', 'email'],
            Setting::MAINTENANCE_FRONTEND => ['nullable'],
        ];
        $validated = $this->validate($request, $rules);

        if (!key_exists(Setting::MANAGEMENT_REGISTRATION, $validated)) {
            $validated[Setting::MANAGEMENT_REGISTRATION] = 'off';
        }
        if (!key_exists(Setting::MAINTENANCE_FRONTEND, $validated)) {
            $validated[Setting::MAINTENANCE_FRONTEND] = 'off';
        }

        try {
            return DB::transaction(function () use ($request, $validated) {
                foreach ($validated as $key => $value) {
                    if ($value == 'on') {
                        $value = 1;
                    }
                    if ($value == 'off') {
                        $value = 0;
                    }
                    Setting::updateOrCreate(
                        ['setting_key' => $key],
                        ['setting_value' => $value],
                    );
                }
                return redirect()->route('admin.settings')->with([
                    "status" => "success",
                    "message" => "Setting successfully updated"
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => 'Update setting failed'
            ]);
        }

    }
}
