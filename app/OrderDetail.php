<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
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
}
