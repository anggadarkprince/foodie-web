<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $visible = ['id', 'category', 'description', 'icon'];

    /**
     * Get the category full path image.
     *
     * @param string $value
     * @return string
     */
    public function getIconAttribute(string $value)
    {
        return empty($value) ? $value : Storage::disk('public')->url($value);
    }

    /**
     * Get the cuisines for the category.
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }
}
