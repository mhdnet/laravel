<?php

namespace App\Http\Controllers;

use App\Constants\AccountRoles;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\Invite;
use App\Rules\EmailOrPhone;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AccountController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        return AccountResource::collection(Account::paginate());
    }

    /**
     * Create new Account
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Account::class);

        Account::forceCreate($request->all());

        return  Response::synchronize();
    }

    /**
     * Update  Account
     * @param Request $request
     * @param Account $account
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Account $account): JsonResponse
    {
        $this->authorize('update', $account);

        $account->forceFill($request->all())->save();

        return  Response::synchronize();
    }

    /**
     * Invite a client to manage the account
     * @throws AuthorizationException
     */
    public function invite(Request $request, Account $account): string
    {
        $this->authorize('invite', $account);

        $request->validate([
            'email' => ['required', new EmailOrPhone],
        ]);

        $email = $request->input('email');

        if (!Str::contains($email, '@')) {
            $request->replace(
                ['phone' => $email]
            );
        }

        if (!$account->invites()->exists())
            $request->merge(['role' => AccountRoles::ADMIN]);


        /** @var Invite $invite */
        $invite = $account->invites()->firstOrCreate($request->all());

        return $invite->toRoute();

    }

    /**
     * Delete Account
     * @param Account $account
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Account $account): JsonResponse
    {
        $this->authorize('delete', $account);
        // TODO: add delete by admin logic.

        return  Response::synchronize();
    }
}
