<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $visible = ['id', 'category', 'description', 'icon'];

    /**
     * Get the user's first name.
     *
     * @param string $value
     * @return string
     */
    public function getIconAttribute(string $value)
    {
        return url('/') . $value;
    }

    /**
     * Get the cuisines for the category.
     */
    public function cuisines()
    {
        return $this->hasMany(Cuisine::class);
    }
}
