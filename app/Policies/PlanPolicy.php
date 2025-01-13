<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return  $user->can(PermissionsName::PlanCreate);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plan $plan): bool
    {
        return  $user->can(PermissionsName::PlanUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plan $plan): bool
    {
        return   $user->can(PermissionsName::PlanDelete) && Plan::count() > 1;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Plan $plan): bool|null
    {
        return  null;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Plan $plan): bool|null
    {
        return null;
    }
}
