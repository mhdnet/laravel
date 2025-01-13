<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Delegate;
use App\Models\User;

class DelegatePolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionsName::DelegateCreate);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Delegate $delegate): bool
    {
        return $user->can(PermissionsName::DelegateUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Delegate $delegate): bool
    {
        return $user->can(PermissionsName::DelegateDelete);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Delegate $delegate): bool|null
    {
        return null;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Delegate $delegate): bool|null
    {
        return null;
    }
}
