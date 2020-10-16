<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveCuisine;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Restaurant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CuisineController extends Controller
{

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Cuisine::class);
    }

    /**
     * Display a listing of the cuisine.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $cuisines = Cuisine::q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('cuisine.index', compact('cuisines'));
    }

    /**
     * Show the form for creating a new cuisine.
     *
     * @return View
     */
    public function create()
    {
        $restaurants = Restaurant::all();
        $categories = Category::all();

        return view('cuisine.create', compact('restaurants', 'categories'));
    }

    /**
     * Store a newly created cuisine in storage.
     *
     * @param SaveCuisine $request
     * @return RedirectResponse
     */
    public function store(SaveCuisine $request)
    {
        try {
            $request->merge(['price' => extract_number($request->input('price'))]);
            $request->merge(['discount' => extract_number($request->input('discount'))]);

            $file = $request->file('image');
            if (!empty($file)) {
                $uploadPath = 'cuisines/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $request->merge(['image' => $path]);
            }

            $cuisine = Cuisine::create($request->input());

            return redirect()->route('admin.cuisines.index')->with([
                "status" => "success",
                "message" => "Cuisine {$cuisine->cuisine} successfully created"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => 'Save cuisine failed'
            ]);
        }
    }

    /**
     * Display the specified cuisine.
     *
     * @param Cuisine $cuisine
     * @return View
     */
    public function show(Cuisine $cuisine)
    {
        return view('cuisine.show', compact('cuisine'));
    }

    /**
     * Show the form for editing the specified cuisine.
     *
     * @param Cuisine $cuisine
     * @return View
     */
    public function edit(Cuisine $cuisine)
    {
        $restaurants = Restaurant::all();
        $categories = Category::all();

        return view('cuisine.edit', compact('cuisine', 'restaurants', 'categories'));
    }

    /**
     * Update the specified cuisine in storage.
     *
     * @param SaveCuisine $request
     * @param Cuisine $cuisine
     * @return RedirectResponse
     */
    public function update(SaveCuisine $request, Cuisine $cuisine)
    {
        try {
            $request->merge(['price' => extract_number($request->input('price'))]);
            $request->merge(['discount' => extract_number($request->input('discount'))]);

            $file = $request->file('image');
            if (!empty($file)) {
                $uploadPath = 'cuisines/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $request->merge(['image' => $path]);

                // delete old file
                if (!empty($cuisine->image)) {
                    Storage::disk('public')->delete($cuisine->image);
                }
            }

            $cuisine->fill($request->input());
            $cuisine->save();

            return redirect()->route('admin.cuisines.index')->with([
                "status" => "success",
                "message" => "Cuisine {$cuisine->title} successfully updated"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => 'Update cuisine failed'
            ]);
        }
    }

    /**
     * Remove the specified cuisine from storage.
     *
     * @param Cuisine $cuisine
     * @return RedirectResponse
     */
    public function destroy(Cuisine $cuisine)
    {
        try {
            $cuisine->delete();
            return redirect()->route('admin.cuisines.index')->with([
                "status" => "warning",
                "message" => "Cuisine {$cuisine->cuisine} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => "Delete cuisine failed"
            ]);
        }
    }
}
