<?php

namespace App\Models\CuisineSearch\Filters;

use App\Models\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryName implements Filter
{
    /**
     * Filter by category.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->where('categories.category', $value);
    }
}
