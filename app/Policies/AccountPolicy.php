<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\{Account, Client, User};

class AccountPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionsName::AccountCreate) ||
            ($user instanceof Client && $user->account_limit > $user->accounts()->count());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Account $account): bool
    {
        return $user->can(PermissionsName::AccountUpdate) ||
            $this->isOwnerOfAccount($user, $account);
    }

    /**
     * Determine whether the user can invite another user to account.
     */
    public function invite(User $user, Account $account): bool
    {
        return $user->can(PermissionsName::AccountInvite) ||
            $this->isOwnerOfAccount($user, $account);
    }
 /**
     * Determine whether the user can update the model.
     */
    public function switch(Client $user, Account $account): bool
    {
        return  $user->accounts()->whereKey($account->id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Account $account): bool
    {
        return $user->can(PermissionsName::AccountDelete) ||
            $this->isOwnerOfAccount($user, $account);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Account $account): bool|null
    {
        return null;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Account $account): bool|null
    {
        return null;
    }

    protected function isOwnerOfAccount(User $user, mixed $id): bool
    {
        return $user instanceof Client &&  $user->isOwnerOfAccount($id);
    }
}
