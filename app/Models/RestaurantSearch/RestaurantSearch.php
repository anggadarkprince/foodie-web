<?php

namespace App\Models\RestaurantSearch;

use App\Models\Cuisine;
use App\Models\Restaurant;
use App\Models\Search\SearchableTrait;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RestaurantSearch
{
    use SearchableTrait;

    /**
     * Get base model.
     *
     * @param Request $filters
     * @return mixed
     */
    public function getBaseModel(Request $filters)
    {
        $builder = Restaurant::query()
            ->select([
                'restaurants.id',
                'restaurants.user_id',
                'restaurants.name',
                'restaurants.address',
                'restaurants.image',
                'restaurants.lat',
                'restaurants.lng',
            ]);

        if ($filters->has('query') || $filters->has('cuisine_name')) {
            $builder
                ->distinct()
                ->join('cuisines', 'cuisines.restaurant_id', '=', 'restaurants.id');
        }

        if (!$filters->has('current_location')) {
            $builder->selectRaw('null AS distance');
        } else {
            $fromLocation = explode(',', $filters->input('current_location'));
            $builder->selectRaw('SQRT(
                POW(69.1 * (lat - ?), 2) +
                POW(69.1 * (? - lng) * COS(lat / 57.3), 2)
            ) * 1609.34 AS distance', $fromLocation);
        }

        return $builder;
    }

    /**
     * Get restaurant result set.
     *
     * @param Builder $builder
     * @return LengthAwarePaginator
     */
    protected function getResult(Builder $builder)
    {
        $restaurantQuery = $builder->paginate(5);

        $restaurantQuery->getCollection()->transform(function ($restaurant) {
            $restaurant['user'] = User::find($restaurant['user_id'], [
                'id', 'name', 'avatar', 'email'
            ]);
            $restaurant['cuisines'] = Cuisine::where('restaurant_id', $restaurant['id'])->select([
                'id', 'cuisine', 'description', 'image', 'price', 'discount'
            ])->get();
            $restaurant->makeHidden('user_id');
            return $restaurant;
        });

        return $restaurantQuery;
    }
}
