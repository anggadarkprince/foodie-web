<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $visible = ['id', 'category', 'description', 'icon'];
    protected $fillable = ['category', 'description', 'icon'];

    /**
     * Get the category full path image.
     *
     * @param string $value
     * @return string
     */
    public function getIconAttribute(string $value)
    {
        return empty($value) ? url('/img/no-image.png') : Storage::disk('public')->url($value);
    }

    /**
     * Get the cuisines for the category.
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }

    /**
     * Scope a query to only include category that match the query.
     *
     * @param Builder $query
     * @param string $q
     * @return Builder
     */
    public function scopeQ(Builder $query, $q = '')
    {
        if (empty($q)) {
            return $query;
        }
        return $query->where(function (Builder $query) use ($q) {
            $query->where('categories.category', 'LIKE', '%' . $q . '%');
            $query->orWhere('categories.description', 'LIKE', '%' . $q . '%');
        });
    }

    /**
     * Scope a query to sort category by specific column.
     *
     * @param Builder $query
     * @param $sortBy
     * @param string $sortMethod
     * @return Builder
     */
    public function scopeSort(Builder $query, $sortBy = 'categories.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'categories.created_at';
        }
        if (empty($sortMethod)) {
            $sortMethod = 'desc';
        }
        return $query->orderBy($sortBy, $sortMethod);
    }

    /**
     * Scope a query to only include category of a greater date creation.
     *
     * @param Builder $query
     * @param $dateFrom
     * @return Builder
     */
    public function scopeDateFrom(Builder $query, $dateFrom)
    {
        if (empty($dateFrom)) return $query;

        try {
            $formattedData = Carbon::parse($dateFrom)->format('Y-m-d');
            return $query->where(DB::raw('DATE(categories.created_at)'), '>=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }

    /**
     * Scope a query to only include category of a less date creation.
     *
     * @param Builder $query
     * @param $dateTo
     * @return Builder
     */
    public function scopeDateTo(Builder $query, $dateTo)
    {
        if (empty($dateTo)) return $query;

        try {
            $formattedData = Carbon::parse($dateTo)->format('Y-m-d');
            return $query->where(DB::raw('DATE(categories.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }
}
