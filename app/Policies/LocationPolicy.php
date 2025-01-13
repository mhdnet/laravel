<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Location;
use App\Models\User;

class LocationPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool|null
    {
        return  true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $location): bool|null
    {
        return  $user->can(PermissionsName::LocationUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $location): bool|null
    {
        return  $user->can(PermissionsName::LocationDelete);
    }


}
