<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuisineImage extends Model
{
    protected $visible = ['id', 'image', 'title'];

    /**
     * Get the cuisine image.
     *
     * @param mixed $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return empty($value) ? $value : url('/') . $value;
    }

    /**
     * Get the cuisine of cuisine image.
     */
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
