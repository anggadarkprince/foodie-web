<?php

namespace App\CuisineSearch\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RestaurantId implements Filter
{
    /**
     * Filter by restaurant id.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->where('cuisines.restaurant_id', $value);
    }
}
