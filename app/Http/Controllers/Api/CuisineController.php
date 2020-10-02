<?php

namespace App\Http\Controllers\Api;

use App\Cuisine;
use App\CuisineImage;
use App\CuisineSearch\CuisineSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DestroyCuisine;
use App\Http\Requests\Api\StoreCuisine;
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
     * Discover nearby cuisine data with default in radius 5KM,
     * we could add constraint for `current_location` param.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function nearby(Request $request)
    {
        if (!$request->has('nearby')) {
            $request->request->add(['nearby' => 5000]);
        }

        $nearbyCuisineQuery = (new CuisineSearch)->apply($request);

        return response()->json($nearbyCuisineQuery);
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
     * @param StoreCuisine $request
     * @return JsonResponse
     */
    public function store(StoreCuisine $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $restaurant = $request->user()->restaurant;

                // store cuisine data
                $file = $request->file('image');
                $uploadPath = 'cuisines/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');

                $cuisineInput = $request->except(['images', 'image']);
                $cuisineInput['image'] = $path;

                $newCuisine = new Cuisine($cuisineInput);
                $cuisine = $restaurant->cuisines()->save($newCuisine);

                // store cuisine detail
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

                return response()->json($cuisine);
            });
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }

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
