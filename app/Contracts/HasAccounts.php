<?php

namespace App\Contracts;

use App\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasAccounts
{
    public function accounts(): BelongsToMany;

    public function currentAccount(): mixed;

    public function isOwnerOfAccount(mixed $account = null): bool;


}
