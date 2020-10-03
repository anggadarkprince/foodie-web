<?php

namespace App\Http\Controllers\Api;

use App\Models\Cuisine;
use App\Models\CuisineImage;
use App\Models\CuisineSearch\CuisineSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DestroyCuisine;
use App\Http\Requests\Api\SaveCuisine;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Discover cuisine with many parameter filters.
 *
 * Class CuisineController
 * @package App\Http\Controllers\Api
 */
class CuisineController extends Controller
{
    /**
     * Discover cuisine data.
     * Search params:
     *  category_name (string): find exact category name
     *  cuisine_name (string): query part of name cuisine
     *  nearby (int): radius distance max in meters
     *  restaurant_id (int): find by restaurant id
     *  query (string): search multiple field such as cuisine name, description, category or restaurant
     *  current_location (string,string): lat,lng location of user optional param for distance calculation
     *
     * Example:
     *  /api/cuisines/nearby?current_location=-7.2556993,112.7338642&nearby=2000&query=gresik
     *  will search `cuisines` that owned by restaurant nearby in radius 2KM from `current_location`
     *  and query restaurants.name, category, cuisine, cuisines.description and restaurants.address match "gresik"
     * Result:
     *  Cuisine with name "Pudak gresik enak" would match
     *  Restaurant with name "Cafe gresik" would match
     *  etc...
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function discovery(Request $request)
    {
        $cuisineQuery = (new CuisineSearch())->apply($request);

        return response()->json($cuisineQuery);
    }

    /**
     * Show single cuisine data in detail.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $cuisine = Cuisine::with([
            'cuisineImages' => function($query) {
                return $query->select(['id', 'cuisine_id', 'image', 'title']);
            },
            'category' => function($query) {
                return $query->select(['id', 'category', 'icon', 'description']);
            },
            'restaurant' => function($query) {
                return $query->select(['id', 'name', 'address', 'image', 'lat', 'lng']);
            },
        ])->findOrFail($id);

        return response()->json($cuisine);
    }

    /**
     *Store new cuisine data.
     *
     * @param SaveCuisine $request
     * @return JsonResponse
     */
    public function store(SaveCuisine $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $restaurant = $request->user()->restaurant;

                $cuisine = new Cuisine(['restaurant_id' => $restaurant->id]);
                $result = $this->save($cuisine, $request);

                return response()->json($result);
            });
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'e' => $e->getMessage(),
                'errors' => 'Store cuisine failed, try again or contact administrator'
            ], 500);
        }

    }

    /**
     * Update available cuisine.
     *
     * @param SaveCuisine $request
     * @param Cuisine $cuisine
     * @return JsonResponse
     */
    public function update(SaveCuisine $request, Cuisine $cuisine)
    {
        try {
            return DB::transaction(function () use ($request, $cuisine) {
                $result = $this->save($cuisine, $request);

                return response()->json($result);
            });
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'errors' => 'Update cuisine failed, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Save or update cuisine data.
     *
     * @param Cuisine $cuisine
     * @param Request $request
     * @return Cuisine
     */
    private function save(Cuisine $cuisine, Request $request)
    {
        // store cuisine data
        $cuisineInput = $request->except(['images', 'image']);

        $file = $request->file('image');
        if (!empty($file)) {
            $uploadPath = 'cuisines/' . date('Ym');
            $path = $file->storePublicly($uploadPath, 'public');
            $cuisineInput['image'] = $path;
        }

        $cuisine->fill($cuisineInput);
        $cuisine->save();

        // store cuisine detail
        $cuisine->cuisineImages()->delete();
        $detailInputs = $request->only('images');
        if(!empty($detailInputs)) {
            foreach ($detailInputs['images'] as &$detailFile) {
                $uploadPath = 'cuisine-details/' . date('Ym');
                $detailFile['image'] = $detailFile['image']->storePublicly($uploadPath, 'public');
            }
            $newCuisineImages = collect($detailInputs['images'])->map(function ($item) {
                return new CuisineImage([
                    'image' => $item['image'],
                    'title' => $item['title']
                ]);
            });
            $cuisine->images = $cuisine->cuisineImages()->saveMany($newCuisineImages);
        }

        return $cuisine;
    }

    /**
     * Destroy cuisine data.
     *
     * @param DestroyCuisine $request
     * @param Cuisine $cuisine
     * @return JsonResponse
     */
    public function destroy(DestroyCuisine $request, Cuisine $cuisine)
    {
        try {
            $delete = $cuisine->delete();

            return response()->json(['result' => $delete]);
        }
        catch (Exception $e) {
            return response()->json([
                'result' => false,
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }
}
