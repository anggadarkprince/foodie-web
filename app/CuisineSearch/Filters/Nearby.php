<?php


namespace App\CuisineSearch\Filters;

use App\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Nearby implements Filter
{
    /**
     * Filter nearby restaurant in meters from current location.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->having('distance', '<=', $value);
    }
}
