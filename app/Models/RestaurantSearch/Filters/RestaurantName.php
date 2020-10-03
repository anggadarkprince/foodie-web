<?php

namespace App\Models\RestaurantSearch\Filters;

use App\Models\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RestaurantName implements Filter
{
    /**
     * Query by restaurant name.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->where('restaurants.restaurant', 'LIKE', '%' . $value . '%');
    }
}
