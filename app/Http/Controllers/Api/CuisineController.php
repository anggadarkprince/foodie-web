<?php

namespace App\Http\Controllers\Api;

use App\Cuisine;
use App\CuisineSearch\CuisineSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *  cuisine_name (string): query part of name cuisine name
     *  nearby (int): radius distance max in meters
     *  restaurant_id (int): find by restaurant id
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
        $cuisineQuery = (new CuisineSearch)->apply($request);

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
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
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
}
