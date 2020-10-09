<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'description'];

    /**
     * Get the permission of the group.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
    }
}
