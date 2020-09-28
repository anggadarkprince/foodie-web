<?php

namespace App\CuisineSearch;

use App\Category;
use App\Cuisine;
use App\Restaurant;
use App\Search\SearchableTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CuisineSearch
{
    use SearchableTrait;

    /**
     * Get decorator namespace.
     *
     * @return string
     */
    public function getDecoratorNamespace()
    {
        return 'App\\CuisineSearch\\Filters\\';
    }

    /**
     * Get base model.
     *
     * @param Request $filters
     * @return mixed
     */
    public function getBaseModel(Request $filters)
    {
        $builder = Cuisine::query()
            ->select([
                'cuisines.id',
                'cuisines.cuisine',
                'cuisines.description',
                'cuisines.image',
                'cuisines.restaurant_id',
                'cuisines.category_id',
                'cuisines.price',
                'cuisines.discount',
            ])
            ->join('categories', 'categories.id', '=', 'cuisines.category_id')
            ->join('restaurants', 'restaurants.id', '=', 'cuisines.restaurant_id');

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
     * Get cuisine result set.
     *
     * @param Builder $builder
     * @return LengthAwarePaginator
     */
    protected function getResult(Builder $builder)
    {
        $cuisineQuery = $builder->paginate(5);

        $cuisineQuery->getCollection()->transform(function ($cuisine) {
            $cuisine['category'] = Category::find($cuisine['category_id'], [
                'id', 'category', 'description', 'icon'
            ]);
            $cuisine['restaurant'] = Restaurant::find($cuisine['restaurant_id'], [
                'id', 'name', 'address', 'image', 'lat', 'lng', 'map_location'
            ]);
            $cuisine->makeHidden('category_id');
            $cuisine->makeHidden('restaurant_id');
            return $cuisine;
        });

        return $cuisineQuery;
    }
}
