<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    /**
     * Get the orders for the courier.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
