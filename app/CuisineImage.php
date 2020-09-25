<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuisineImage extends Model
{
    /**
     * Get the cuisine of cuisine image.
     */
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
