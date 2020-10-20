<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveCourierRequest;
use App\Models\Courier;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;
use Throwable;

class CourierController extends Controller
{
    /**
     * CourierController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Courier::class);
    }

    /**
     * Display a listing of the courier.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $couriers = Courier::q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('courier.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new courier.
     *
     * @return View
     */
    public function create()
    {
        $couriers = Courier::all();

        return view('courier.create', compact('couriers'));
    }

    /**
     * Store a newly created courier in storage.
     *
     * @param SaveCourierRequest $request
     * @return RedirectResponse
     */
    public function store(SaveCourierRequest $request)
    {
        try {
            $photo = $request->file('photo');
            $uploadPath = 'couriers/' . date('Ym');
            $request->merge(['photo' => $photo->storePublicly($uploadPath, 'public')]);
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $request->merge(['date_of_birth' => Carbon::parse($request->input('date_of_birth'))->format('Y-m-d')]);

            $courier = Courier::create($request->input());

            $courier->sendEmailVerificationNotification();

            return redirect()->route('admin.couriers.index')->with([
                "status" => "success",
                "message" => "Courier {$courier->name} successfully created"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified courier.
     *
     * @param Courier $courier
     * @return View
     */
    public function show(Courier $courier)
    {
        $lastDeliveries = $courier->orders()->withTotal()->latest()->take(5)->get();

        return view('courier.show', compact('courier', 'lastDeliveries'));
    }

    /**
     * Show the form for editing the specified courier.
     *
     * @param Courier $courier
     * @return View
     */
    public function edit(Courier $courier)
    {
        return view('courier.edit', compact('courier'));
    }

    /**
     * Update the specified courier in storage.
     *
     * @param Request $request
     * @param Courier $courier
     * @return RedirectResponse
     */
    public function update(Request $request, Courier $courier)
    {
        try {
            if ($request->isNotFilled('password')) {
                $request->request->remove('password');
                $request->request->remove('password_confirmation');
            } else {
                $request->merge(['password' => bcrypt($request->input('password'))]);
            }
            $request->merge(['date_of_birth' => Carbon::parse($request->input('date_of_birth'))->format('Y-m-d')]);

            $photo = $request->file('photo');
            if (!empty($photo)) {
                $uploadPath = 'couriers/' . date('Ym');
                $request->merge(['photo' => $photo->storePublicly($uploadPath, 'public')]);

                if (!empty($courier->photo)) {
                    Storage::disk('public')->delete($courier->photo);
                }
            }

            $courier->fill($request->input());
            $courier->save();

            return redirect()->route('admin.couriers.index')->with([
                "status" => "success",
                "message" => "Courier {$courier->name} successfully updated"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified courier from storage.
     *
     * @param Courier $courier
     * @return RedirectResponse
     */
    public function destroy(Courier $courier)
    {
        try {
            $courier->delete();

            return redirect()->route('admin.couriers.index')->with([
                "status" => "warning",
                "message" => "Courier {$courier->group} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => "Delete courier failed"
            ]);
        }
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        $courier = Courier::findOrFail($request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($courier->getEmailForVerification()))) {
            abort('403');
        }

        if ($courier->markEmailAsVerified()) {
            event(new Verified($courier));
        }

        return redirect('/');
    }
}
