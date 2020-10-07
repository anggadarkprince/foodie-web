<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        return empty($value) ? $value : Storage::disk('public')->url($value);
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
}