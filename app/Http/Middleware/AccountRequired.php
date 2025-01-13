<?php

namespace App\Http\Middleware;

use App\Contracts\HasAccounts;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class AccountRequired
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($user = $request->user()) && $user instanceof HasAccounts && !!$user->currentAccount()) {
            return $next($request);
        }

        throw new NotAcceptableHttpException('No Account found.');
    }
}
