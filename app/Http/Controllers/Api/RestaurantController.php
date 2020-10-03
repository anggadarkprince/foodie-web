<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SaveRestaurant;
use App\Models\Restaurant;
use App\Models\RestaurantSearch\RestaurantSearch;
use Exception;
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
            'user' => function ($query) {
                return $query->select(['id', 'name', 'avatar', 'email']);
            },
            'cuisines' => function ($query) {
                return $query->select(['id', 'cuisine', 'restaurant_id', 'image', 'description', 'price', 'discount']);
            },
        ])->findOrFail($id);

        $restaurants->cuisines->makeHidden('restaurant_id');

        return response()->json($restaurants);
    }

    /**
     * Get user restaurant transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function orders(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $orders = $restaurant->orders();

        if ($request->filled('payment_type')) {
            $orders->paymentType($request->get('payment_type'));
        }

        if ($request->has('active')) {
            $orders->active();
        }

        if ($request->filled('status')) {
            $orders->status($request->get('status'));
        }

        if ($request->has('order_method')) {
            if (in_array($request->get('order_method'), ['oldest', 'latest', 'desc', '-1'])) {
                $orders->latest();
            } else {
                $orders->earlier();
            }
        } else {
            $orders->latest();
        }

        return response()->json($orders->paginate(10));
    }

    /**
     * Get restaurant transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transactions(Request $request)
    {
        $transactions = $request->user()->restaurant->transactions()->latest();

        if ($request->filled('type')) {
            $transactions->type($request->get('type'));
        }

        if ($request->filled('status')) {
            $transactions->status($request->get('status'));
        }

        return response()->json($transactions->paginate(10));
    }

    /**
     * Update restaurant data.
     *
     * @param SaveRestaurant $request
     * @return JsonResponse
     */
    public function update(SaveRestaurant $request)
    {
        try {
            $user = $request->user();

            $restaurantInput = $request->except(['image']);

            $file = $request->file('image');
            if (!empty($file)) {
                $uploadPath = 'restaurants/' . date('Ym');
                $path = $file->storePublicly($uploadPath, 'public');
                $restaurantInput['image'] = $path;
            }
            $restaurant = Restaurant::updateOrCreate(
                ['user_id' => $user->id],
                $restaurantInput
            );

            return response()->json($restaurant);
        } catch (Exception $e) {
            return response()->json([
                'result' => false,
                'errors' => 'Update restaurant failed, try again or contact administrator'
            ], 500);
        }
    }
}
