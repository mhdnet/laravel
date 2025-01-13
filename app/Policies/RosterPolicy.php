<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Roster;
use App\Models\User;

class RosterPolicy
{
    protected function getPermission(User $user, string $name): bool
    {
        $name = str_replace('Policy', '', class_basename($this)). ucfirst($name);

        $permission = constant(PermissionsName::class . "::$name");

        return $user->can($permission);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->getPermission($user,'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Roster $roster): bool
    {
        return $this->getPermission($user,'update') && !$roster->exported_at;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Roster $roster): bool
    {
        return $this->getPermission($user,'delete') && !$roster->exported_at;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Roster $roster): bool|null
    {
        return null;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Roster $roster): bool|null
    {
        return null;
    }
}
