<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission(Permission::RESTAURANT_VIEW);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function view(User $user, Restaurant $restaurant)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::RESTAURANT_CREATE);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function update(User $user, Restaurant $restaurant)
    {
        return $user->hasPermission(Permission::RESTAURANT_EDIT);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function delete(User $user, Restaurant $restaurant)
    {
        return $user->hasPermission(Permission::RESTAURANT_DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function restore(User $user, Restaurant $restaurant)
    {
        return $this->delete($user, $restaurant);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function forceDelete(User $user, Restaurant $restaurant)
    {
        return $this->delete($user, $restaurant);
    }
}
