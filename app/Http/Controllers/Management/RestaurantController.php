<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveRestaurant;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RestaurantController extends Controller
{
    /**
     * RestaurantController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Restaurant::class);
    }
    /**
     * Display a listing of the restaurant.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::baseQuery()
            ->q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('restaurant.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new restaurant.
     *
     * @return View
     */
    public function create()
    {
        $users = User::customer()->get();

        return view('restaurant.create', compact('users'));
    }

    /**
     * Store a newly created restaurant in storage.
     *
     * @param SaveRestaurant $request
     * @return RedirectResponse
     */
    public function store(SaveRestaurant $request)
    {
        try {
            $file = $request->file('image');
            if (!empty($file)) {
                $uploadPath = 'restaurants/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $request->merge(['image' => $path]);
            }

            $restaurant = Restaurant::create($request->input());

            return redirect()->route('admin.restaurants.index')->with([
                "status" => "success",
                "message" => "Restaurant {$restaurant->name} successfully created"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Create restaurant failed"
            ]);
        }
    }

    /**
     * Display the specified restaurant.
     *
     * @param Restaurant $restaurant
     * @return View
     */
    public function show(Restaurant $restaurant)
    {
        return view('restaurant.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified restaurant.
     *
     * @param Restaurant $restaurant
     * @return View
     */
    public function edit(Restaurant $restaurant)
    {
        $users = User::customer()->get();

        return view('restaurant.edit', compact('users', 'restaurant'));
    }

    /**
     * Update the specified restaurant in storage.
     *
     * @param SaveRestaurant $request
     * @param Restaurant $restaurant
     * @return RedirectResponse
     */
    public function update(SaveRestaurant $request, Restaurant $restaurant)
    {
        try {
            $file = $request->file('image');
            if (!empty($file)) {
                $uploadPath = 'restaurants/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $request->merge(['image' => $path]);

                // delete old file
                if (!empty($restaurant->image)) {
                    Storage::disk('public')->delete($restaurant->image);
                }
            }

            $restaurant->fill($request->input());
            $restaurant->save();

            return redirect()->route('admin.restaurants.index')->with([
                "status" => "success",
                "message" => "Restaurant {$restaurant->name} successfully updated"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified restaurant from storage.
     *
     * @param Restaurant $restaurant
     * @return RedirectResponse
     */
    public function destroy(Restaurant $restaurant)
    {
        try {
            $restaurant->delete();
            return redirect()->route('admin.restaurants.index')->with([
                "status" => "warning",
                "message" => "Restaurant {$restaurant->name} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => "Delete restaurant failed"
            ]);
        }
    }
}
