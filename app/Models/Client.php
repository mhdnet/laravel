<?php

namespace App\Models;


use App\Constants\AccountRoles;
use App\Contracts\HasAccounts;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends User implements HasAccounts
{
    protected string $guard_name = "client";

    protected Account|null $currentAccount = null;

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class)
            ->withPivot(['role', 'subscribe'])
            ->withTimestamps();
    }

    public function currentAccount(): Account|null
    {
        if (is_null($this->accessToken->account_id)) return null;

        return $this->currentAccount = $this->currentAccount ?:
            $this->accounts()->find($this->accessToken->account_id)->loadMissing('plan');

    }

    public function isOwnerOfAccount(mixed $account = null): bool
    {
        if (!is_null($account)) {
            return !!$this->accounts()
                ->wherePivot('role', AccountRoles::OWNER)
                ->find($account);
        }

        $account = $this->currentAccount();

        return !!$account && $account->pivot->role == AccountRoles::OWNER;
    }

    public function useAccount(int $id): void
    {
        if(!$this->accounts()->whereKey($id)->exists())
            return;
        $this->accessToken->account_id = $id;
        $this->accessToken->save();
    }

    public function createAccount($attributes = []): \Illuminate\Database\Eloquent\Model
    {
        $account = $this->accounts()->create($attributes,  ['role' => AccountRoles::OWNER]);

        if($this->accounts()->count() == 1){
            $this->useAccount($account->id);
        }

        return $this->accounts()->find($account->id);// $account->loadMissing('users');
    }
}
