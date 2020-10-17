<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Restaurant extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'address', 'image', 'lat', 'lng'];

    /**
     * Get the restaurant image cover.
     *
     * @param mixed $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return empty($value) ? url('/img/no-image.png') : Storage::disk('public')->url($value);
    }

    /**
     * Get the cuisines for the restaurant.
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }

    /**
     * Get the user of the restaurant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for the restaurant.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the transactions for the restaurant.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get the order details for the restaurant.
     */
    public function orderDetails()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class);
    }

    /**
     * Get a new query builder instance for scope.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function scopeBaseQuery(Builder $query)
    {
        return $query
            ->select('restaurants.*')
            ->join('users', 'users.id', 'restaurants.user_id');
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
            $query->where('restaurants.name', 'LIKE', '%' . $q . '%');
            $query->orWhere('restaurants.address', 'LIKE', '%' . $q . '%');
            $query->orWhere('users.name', 'LIKE', '%' . $q . '%');
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
    public function scopeSort(Builder $query, $sortBy = 'restaurants.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'restaurants.created_at';
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
            return $query->where(DB::raw('DATE(restaurants.created_at)'), '>=', $formattedData);
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
            return $query->where(DB::raw('DATE(restaurants.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }
}
