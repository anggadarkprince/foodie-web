<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Cuisine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['restaurant_id', 'category_id', 'cuisine', 'image', 'description', 'price', 'discount'];

    /**
     * Get the cuisine image.
     *
     * @param mixed $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return empty($value) ? url('/img/no-image.png') : Storage::disk('public')->url($value);
    }

    /**
     * Get the category of cuisine.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the category of cuisine.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the cuisine images for the cuisine.
     */
    public function cuisineImages()
    {
        return $this->hasMany(CuisineImage::class);
    }

    /**
     * Get the order details for the cuisine.
     */
    public function orderDetails()
    {
        return $this->hasMany(CuisineImage::class);
    }

    /**
     * Get the order for the cuisine.
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderDetail::class);
    }

    /**
     * Scope a query to only include group that match the query.
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
            $query->where('cuisines.cuisine', 'LIKE', '%' . $q . '%');
            $query->orWhereHas('restaurant', function (Builder $query) use ($q) {
                $query->where('restaurants.name', 'LIKE', '%' . $q . '%');
                $query->orWhere('address', 'LIKE', '%' . $q . '%');
            });
            $query->orWhereHas('category', function (Builder $query) use ($q) {
                $query->where('category', 'LIKE', '%' . $q . '%');
            });
        });
    }

    /**
     * Scope a query to sort group by specific column.
     *
     * @param Builder $query
     * @param $sortBy
     * @param string $sortMethod
     * @return Builder
     */
    public function scopeSort(Builder $query, $sortBy = 'cuisines.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'cuisines.created_at';
        }
        if (empty($sortMethod)) {
            $sortMethod = 'desc';
        }
        return $query->orderBy($sortBy, $sortMethod);
    }

    /**
     * Scope a query to only include group of a greater date creation.
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
            return $query->where(DB::raw('DATE(cuisines.created_at)'), '>=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }

    /**
     * Scope a query to only include group of a less date creation.
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
            return $query->where(DB::raw('DATE(cuisines.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }
}
