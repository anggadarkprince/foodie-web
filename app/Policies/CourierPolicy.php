<?php

namespace App\Policies;

use App\Models\Courier;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierPolicy
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
        return $user->hasPermission(Permission::COURIER_VIEW);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Courier $courier
     * @return mixed
     */
    public function view(User $user, Courier $courier)
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
        return $user->hasPermission(Permission::COURIER_CREATE);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Courier $courier
     * @return mixed
     */
    public function update(User $user, Courier $courier)
    {
        return $user->hasPermission(Permission::COURIER_EDIT);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Courier $courier
     * @return mixed
     */
    public function delete(User $user, Courier $courier)
    {
        return $user->hasPermission(Permission::COURIER_DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Courier $courier
     * @return mixed
     */
    public function restore(User $user, Courier $courier)
    {
        return $this->delete($user, $courier);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Courier $courier
     * @return mixed
     */
    public function forceDelete(User $user, Courier $courier)
    {
        return $this->delete($user, $courier);
    }
}
