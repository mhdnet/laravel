<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Constants\RolesName;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Laravel\Sanctum\Contracts\HasApiTokens;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        \Gate::after(function (User $user, string $ability){
            return $user->hasRole(RolesName::SUPER);
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return '';
        });

        VerifyEmail::toMailUsing(function ($notifiable) {
            return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Please enter this confirmation code in the app.'))
                ->line($notifiable->otp)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject(Lang::get('Reset Password Code'))
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->line(Lang::get('If it was you, enter this confirmation code in the app.'))
                ->line($token)
                ->line(Lang::get('If you did not request a password reset, no further action is required.'));
        });
    }
}
