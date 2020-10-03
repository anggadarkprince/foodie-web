<?php

namespace App\Models\RestaurantSearch\Filters;

use App\Models\Search\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Query implements Filter
{
    /**
     * Query by multiple fields.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder
     */
    public static function apply(Builder $builder, $value, Request $request)
    {
        return $builder->where(function (Builder $query) use ($value) {
            $query->where('restaurants.name', 'LIKE', '%' . $value . '%');
            $query->orWhere('restaurants.address', 'LIKE', '%' . $value . '%');
            $query->orWhere('cuisines.cuisine', 'LIKE', '%' . $value . '%');
            $query->orWhere('cuisines.description', 'LIKE', '%' . $value . '%');
        });
    }
}
