<?php

namespace App\Models\CuisineSearch\Filters;

use App\Models\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CuisineName implements Filter
{
    /**
     * Query by cuisine name.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->where('cuisines.cuisine', 'LIKE', '%' . $value . '%');
    }
}
