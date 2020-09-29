<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Restaurant;
use App\RestaurantSearch\RestaurantSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Discover restaurant data.
     * Search params:
     *  restaurant_name (string): query part of restaurant name
     *  cuisine_name (string): query part of cuisine that owned by restaurant
     *  query (string): search multiple field such as cuisine name, description, category or restaurant
     *  nearby (int): radius distance max in meters
     *  current_location (string,string): lat,lng location of user optional param for distance calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function discovery(Request $request)
    {
        $cuisineQuery = (new RestaurantSearch())->apply($request);

        return response()->json($cuisineQuery);
    }

    /**
     * Show single restaurant data in detail.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $restaurants = Restaurant::with([
            'user' => function($query) {
                return $query->select(['id', 'name', 'avatar', 'email']);
            },
            'cuisines' => function($query) {
                return $query->select(['id', 'cuisine', 'restaurant_id', 'image', 'description', 'price', 'discount']);
            },
        ])->findOrFail($id);

        $restaurants->cuisines->makeHidden('restaurant_id');

        return response()->json($restaurants);
    }

    /**
     * Get restaurant transactions.
     *
     * @param Request $request
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function orders(Request $request, Restaurant $restaurant)
    {
        $orders = $restaurant->orders()->orderBy('orders.created_at', 'desc');

        if ($request->has('payment_type')) {
            $orders->where('orders.payment_type', $request->get('payment_type'));
        }

        return response()->json($orders->paginate(10));
    }
}
