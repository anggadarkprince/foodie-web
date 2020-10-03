<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'cuisine_id', 'cuisine', 'category', 'description',
        'price', 'discount', 'rating'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * Get the order of the order detail.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the cuisine of the order detail.
     */
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    /**
     * Get order number.
     *
     * @param array $item
     * @return string
     */
    public static function mapFromRequest(array $item)
    {
        $cuisine = Cuisine::find($item['cuisine_id']);
        return new OrderDetail([
            'cuisine_id' => $cuisine->id,
            'cuisine' => $cuisine->cuisine,
            'category' => $cuisine->category->category,
            'quantity' => $item['quantity'],
            'description' => $item['description'],
            'price' => $cuisine->price,
            'discount' => $cuisine->discount,
        ]);
    }
}
