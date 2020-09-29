<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        return empty($value) ? $value : Storage::disk('public')->url($value);
    }

    /**
     * Get the cuisine of cuisine image.
     */
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
