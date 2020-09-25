<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
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
     * Get the order details for the restaurant.
     */
    public function orderDetails()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class);
    }
}
