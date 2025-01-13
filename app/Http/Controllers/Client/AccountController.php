<?php

namespace App\Http\Controllers\Client;

use App\Constants\AccountRoles;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\AccountResource;
use App\Models\Account;
use App\Models\Invite;
use App\Rules\EmailOrPhone;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('account.required')->except('store');
    }

    /**
     * Create new Account
     * @param Request $request
     * @return AccountResource
     * @throws AuthorizationException
     */
    public function store(Request $request): AccountResource
    {
        $this->authorize('create', Account::class);

        $account = $request->user()->createAccount($request->all());


        return new AccountResource($account);

    }

    public function update(Request $request, Account $account) {}

    /**
     * Switch between accounts
     * @param Request $request
     * @param Account $account
     * @return void
     * @throws AuthorizationException
     */
    public function show(Request $request, Account $account): void
    {
        $this->authorize('switch', $account);

        $request->user()->useAccount($account->id);
        // TODO: return account if necessary.

    }

    /**
     * Update current Account
     * @param Request $request
     * @param Account $account
     * @return string
     * @throws AuthorizationException
     */
    public function invite(Request $request, Account $account): string
    {
        $this->authorize('invite', $account);

        $request->validate([
            'email' => ['required', new EmailOrPhone,],
        ]);

        $email = $request->input('email');

        if (!Str::contains($email, '@')) {
            $request->replace(
                ['phone' => $email]
            );
        }

        $request->merge(['role' => AccountRoles::ADMIN]);

        /** @var Invite $invite */
        $invite = $account->invites()->firstOrCreate($request->all());

        return  $invite->toRoute();

    }


    /**
     * Delete Account
     * @param Request $request
     * @param Account $account
     * @return void
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Account $account): void
    {
        $this->authorize('delete', $account);

        // TODO: Scheduler delete Account
    }
}
