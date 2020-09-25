<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    /**
     * Get the category of cuisine.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
}
