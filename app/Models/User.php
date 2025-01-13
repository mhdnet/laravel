<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\RolesName;
use App\Rules\PhoneRule;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Watson\Validating\ValidatingTrait;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, HasApiTokens, HasFactory, Notifiable, HasRoles, ValidatingTrait, SoftDeletes;

    protected $table = 'users';

    protected $with =['roles'];
//    protected string $guard_name = "web";


    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
    ];

    protected bool $throwValidationExceptions = true;


    protected static function booted(): void
    {
        parent::booted();

        $type = strtolower(class_basename(static::class));

        if ($type != 'user') {
            static::addGlobalScope('type', function (Builder $query) use ($type) {
                $query->scopes(['role' => $type]);
            });

            static::created(function (self $user) use ($type) {
                $user->assignRole($type);

                if (($type == RolesName::ADMIN && config('settings.admin_as_client')) ||
                    ($type == RolesName::DELEGATE && config('settings.delegate_as_client'))) {
                    Client::withoutType()->find($user->id)
                        ->assignRole(RolesName::CLIENT);
                }
            });
        }
    }

    public function scopeWithoutType(Builder $query): Builder
    {
        return $query->withoutGlobalScope('type');
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'nullable|email|unique:users',
            'phone' => ['required_without:email', new PhoneRule, 'unique:users'],
            'password' => 'required|min:8',
            'active' => 'sometimes|required|boolean',
        ];
    }
}
