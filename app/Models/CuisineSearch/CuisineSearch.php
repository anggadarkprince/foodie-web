<?php

namespace App\Models\CuisineSearch;

use App\Models\Category;
use App\Models\Cuisine;
use App\Models\CuisineImage;
use App\Models\Restaurant;
use App\Models\Search\SearchableTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CuisineSearch
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
                'id', 'name', 'address', 'image', 'lat', 'lng'
            ]);
            $cuisine['cuisine_images'] = CuisineImage::where('cuisine_id', $cuisine['id'])->select([
                'image', 'title'
            ])->get();
            $cuisine->makeHidden('category_id');
            $cuisine->makeHidden('restaurant_id');
            return $cuisine;
        });

        return $cuisineQuery;
    }
}
