<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\HasAccounts;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserData;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Delegate;
use App\Models\Invite;
use App\Models\User;
use App\Rules\EmailOrPhone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OwenIt\Auditing\Exceptions\AuditingException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): string
    {
        $request->validate([
            'email' => ['required', new EmailOrPhone],
            'password' => 'required',
        ]);

        $email = strtolower($request->str('email'));

        $user = $this->getModel($request);

        $user = $user::where('active', true)
            ->where(function (Builder $query) use ($email) {
                $query->where('email', $email)->orWhere('phone', $email);
            })
            ->first();


        if (!$user || !Hash::check($request->str('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        return $this->getToken($request, $user);

    }

    public function register(Request $request): string
    {

        $email = $request->str('email');

        $request->validate([
            'email' => ['required', new EmailOrPhone],
            'password' => 'required|min:8',
            'invite_token' => ['required', Rule::exists('invites', 'ulid')
                ->where(function (\Illuminate\Database\Query\Builder $query) use ($email) {
                    $query->where('email', $email)->orWhere('phone', $email);
                })
            ],
        ]);

        if (!Str::contains($email, '@')) {
            $request->replace(
                ['phone' => $email, 'password' => $request->str('password'),]
            );
        }

        if (!$request->filled('name'))
            $request->merge(['name' => 'user_' . +User::max('id') + 1000]);

        $user = Client::create($request->all());

        return $this->getToken($request, $user);
    }

    /**
     * @throws AuditingException
     */
    protected function getToken(Request $request, User $user): string
    {
        $tokenName = $this->createTokenName($request, $user);

//        if($old = $user->tokens()->where('name', $tokenName)->first()) {

//        }

        $user->tokens()->where('name', $tokenName)->delete();

        $token = $user->createToken($tokenName);

        if ($user instanceof HasAccounts) {
            if (($invite_token = $request->str('invite_token')) && $invite = Invite::where(function ($query) use ($user) {
                    $query->where('email', $user->email)->orWhere('phone', $user->phone);
                })->firstWhere('ulid', $invite_token)) {

                $invite->account->addUser($user, $invite->role);

                $invite->delete();
            }

            if ($user->accounts()->exists())
                tap($token->accessToken, function ($token) use ($user) {
                    $token->account_id = $user->accounts()->first()->id;
                })->save();

        }

        return $token->plainTextToken;
    }

    /**
     * Get current user data
     */
    public function user(Request $request): UserData
    {
        return new  UserData($request->user());
    }

    /**
     * Get model from route
     */
    protected function getModel(Request $request): string|User
    {
        if ($request->isAdminRoute()) return Admin::class;
        if ($request->isDelegateRoute()) return Delegate::class;
        return Client::class;

    }

    /**
     * Create token name
     */
    protected function createTokenName(Request $request, $user): string
    {
        return join('.', [
            strtolower(class_basename($user)),
            $request->input('device_name', $request->userAgent() ?? 'token'),
        ]);
    }

}
