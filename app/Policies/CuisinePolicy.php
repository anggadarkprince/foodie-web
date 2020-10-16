<?php

namespace App\Policies;

use App\Models\Cuisine;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CuisinePolicy
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
        return $user->hasPermission(Permission::CUISINE_VIEW);
    }

    /**
     * Determine whether the user can view the cuisine.
     *
     * @param User $user
     * @param Cuisine $cuisine
     * @return mixed
     */
    public function view(User $user, Cuisine $cuisine)
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
        return $user->hasPermission(Permission::CUISINE_CREATE);
    }

    /**
     * Determine whether the user can update the cuisine.
     *
     * @param User $user
     * @param Cuisine $cuisine
     * @return mixed
     */
    public function update(User $user, Cuisine $cuisine)
    {
        return $user->hasPermission(Permission::CUISINE_EDIT);
    }

    /**
     * Determine whether the user can delete the cuisine.
     *
     * @param User $user
     * @param Cuisine $cuisine
     * @return mixed
     */
    public function delete(User $user, Cuisine $cuisine)
    {
        return $user->hasPermission(Permission::CUISINE_DELETE);
    }

    /**
     * Determine whether the user can restore the cuisine.
     *
     * @param User $user
     * @param Cuisine $cuisine
     * @return mixed
     */
    public function restore(User $user, Cuisine $cuisine)
    {
        return $this->delete($user, $cuisine);
    }

    /**
     * Determine whether the user can permanently delete the cuisine.
     *
     * @param User $user
     * @param Cuisine $cuisine
     * @return mixed
     */
    public function forceDelete(User $user, Cuisine $cuisine)
    {
        return $this->delete($user, $cuisine);
    }
}
