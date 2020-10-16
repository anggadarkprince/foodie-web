<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SaveCategory;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoryController extends Controller
{

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }

    /**
     * Display a listing of the category.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $categories = Category::q($request->get('q'))
            ->sort($request->get('sort_by'), $request->get('sort_method'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->paginate();

        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return View
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param SaveCategory $request
     * @return RedirectResponse
     */
    public function store(SaveCategory $request)
    {
        try {
            $inputCategory = $request->validated();
            $inputCategory['icon'] = $request->file('icon')->storePublicly('categories', 'public');

            $category = Category::create($inputCategory);

            return redirect()->route('admin.categories.index')->with([
                "status" => "success",
                "message" => "Category {$category->category} successfully created"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified category.
     *
     * @param Category $category
     * @return View
     */
    public function show(Category $category)
    {
        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param Category $category
     * @return View
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param SaveCategory $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(SaveCategory $request, Category $category)
    {
        try {
            $file = $request->file('icon');
            if (!empty($file)) {
                $uploadedIcon = $file->storePublicly('categories', 'public');
                $request->merge(['icon' => $uploadedIcon]);

                if (!empty($category['icon'])) {
                    Storage::disk('public')->delete($category['avatar']);
                }
            }

            $category = new Category($request->all());

            return redirect()->route('admin.categories.index')->with([
                "status" => "success",
                "message" => "Category {$category->category} successfully updated"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with([
                "status" => "failed",
                "message" => "Update category failed"
            ]);
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('admin.categories.index')->with([
                "status" => "warning",
                "message" => "Category {$category->category} successfully deleted"
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with([
                "status" => "failed",
                "message" => 'Delete category failed'
            ]);
        }
    }
}
