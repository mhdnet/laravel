<?php

namespace App\Models;


use App\Contracts\HasAccounts;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Delegate extends User implements HasAccounts
{
    protected string $guard_name = "delegate";
    protected Business|null $currentAccount = null;

    protected $with = ['accounts'];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Business::class)
            ->withPivot(['role', 'fare'])
            ->withTimestamps();
    }


    public function currentAccount(): Business|null
    {
        if (is_null($this->accessToken->account_id)) return null;

        return $this->currentAccount = $this->currentAccount ?:
            $this->accounts()->find($this->accessToken->account_id);

    }

    public function isOwnerOfAccount(mixed $account = null): bool
    {
        if (!is_null($account)) {
            return !!$this->accounts()->wherePivot('role', 'owner')->find($account);
        }

        $account = $this->currentAccount();

        return !!$account && $account->pivot->role == 'owner';
    }

    public function attachToBusiness(int $fare, int $business = null, bool $owner = false): void
    {
        if ($business)
            $this->accounts()->sync([$business => ['fare' => $fare, 'role' => $owner ? 'owner' : null]], false);
//        elseif($exists = $this->accounts()->first()) {
//            $exists->users()->sync([
//                $this->id => ['fare' => $fare],
//            ], false);
//        }
        else
            Business::default()->users()->sync([
                $this->id => ['fare' => $fare],
            ], false);

    }

}
