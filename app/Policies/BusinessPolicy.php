<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Constants\RolesName;
use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionsName::BusinessCreate);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Business $business): bool
    {
        return $user->can(PermissionsName::BusinessUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Business $business): bool
    {
        return $user->can(PermissionsName::BusinessDelete);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Business $business): bool|null
    {
        return null;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Business $business): bool|null
    {
        return null;
    }
}
