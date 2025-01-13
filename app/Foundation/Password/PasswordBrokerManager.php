<?php

namespace App\Foundation\Password;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\PasswordBrokerManager as PasswordBrokerManagerBase;


class PasswordBrokerManager extends PasswordBrokerManagerBase
{
    protected function createTokenRepository(array $config): DatabaseTokenRepository
    {
        $key = $this->app['config']['app.key'];
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $connection = $config['connection'] ?? null;
        // return an instance of your new repository
        // it's in the same namespace, no need to alias it
        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire']
        );
    }

    public function sendResetLink(array $credentials, Closure $callback = null): string
    {
        return $this->broker()->sendResetLink($credentials, $callback);
    }

    public function reset(array $credentials, Closure $callback)
    {
        return $this->broker()->reset($credentials, $callback);
    }
}
