<?php

namespace App\Models\CuisineSearch\Filters;

use App\Models\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PriceMin implements Filter
{
    /**
     * Filter by price minimum.
     *
     * @param Builder $builder
     * @param mixed $price
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $price, Request $request)
    {
        if ($price != '') {
            $builder->where('cuisines.price', '>=', $price);
        }

        return $builder;
    }
}
