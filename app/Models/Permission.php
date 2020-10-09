<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    const GROUP_VIEW = 'group-view';
    const GROUP_CREATE = 'group-create';
    const GROUP_EDIT = 'group-edit';
    const GROUP_DELETE = 'group-delete';

    const USER_VIEW = 'user-view';
    const USER_CREATE = 'user-create';
    const USER_EDIT = 'user-edit';
    const USER_DELETE = 'user-delete';

    const CATEGORY_VIEW = 'category-view';
    const CATEGORY_CREATE = 'category-create';
    const CATEGORY_EDIT = 'category-edit';
    const CATEGORY_DELETE = 'category-delete';

    const RESTAURANT_VIEW = 'restaurant-view';
    const RESTAURANT_CREATE = 'restaurant-create';
    const RESTAURANT_EDIT = 'restaurant-edit';
    const RESTAURANT_DELETE = 'restaurant-delete';

    const CUISINE_VIEW = 'cuisine-view';
    const CUISINE_CREATE = 'cuisine-create';
    const CUISINE_EDIT = 'cuisine-edit';
    const CUISINE_DELETE = 'cuisine-delete';

    const COURIER_VIEW = 'courier-view';
    const COURIER_CREATE = 'courier-create';
    const COURIER_EDIT = 'courier-edit';
    const COURIER_DELETE = 'courier-delete';

    const ORDER_VIEW = 'order-view';
    const ORDER_CREATE = 'order-create';
    const ORDER_EDIT = 'order-edit';
    const ORDER_DELETE = 'order-delete';

    const ACCOUNT_EDIT = 'account-edit';
    const SETTING_EDIT = 'setting-edit';

    /**
     * Get the group of the permission.
     */
    public function permissions()
    {
        return $this->belongsToMany(Group::class, 'group_permissions');
    }
}
