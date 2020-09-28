<?php


namespace App\CuisineSearch\Filters;


use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;

class RestaurantId implements Filter
{

    public static function apply(Builder $builder, $value)
    {
        return $builder->where('cuisines.restaurant_id', $value);
    }
}
