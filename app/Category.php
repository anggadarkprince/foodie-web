<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Get the cuisines for the category.
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }
}
