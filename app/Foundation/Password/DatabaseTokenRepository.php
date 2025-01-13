<?php

namespace App\Foundation\Password;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as DatabaseTokenRepositoryBase;
use Illuminate\Support\Carbon;

class DatabaseTokenRepository extends DatabaseTokenRepositoryBase
{
    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return (string) mt_rand(100000, 999999);
    }

    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return $record &&
            ! $this->tokenExpired($record['created_at']) &&
            $token == $record['token'];
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $email
     * @param  string  $token
     * @return array
     */
    protected function getPayload($email, $token)
    {
        return ['email' => $email, 'token' => $token, 'created_at' => new Carbon];
    }
}
