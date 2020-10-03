<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @param Request $request
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value, Request $request);
}
