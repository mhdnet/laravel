<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Governorate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GovernoratePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionsName::GovernorateCreate);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Governorate $governorate): bool
    {
        return $user->can(PermissionsName::GovernorateUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Governorate $governorate): bool
    {
        return $user->can(PermissionsName::GovernorateDelete);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Governorate $governorate): bool|null
    {
        return null;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Governorate $governorate): bool|null
    {
        return null;
    }
}
