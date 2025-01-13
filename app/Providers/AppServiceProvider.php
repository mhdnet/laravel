<?php

namespace App\Providers;

use App\Models\{Account,
    Admin,
    Business,
    Client,
    Delegate,
    Governorate,
    Location,
    Order,
    Payment,
    Plan,
    Roster,
    Statement
};

use App\Contracts\HasAccounts;
use App\Http\Controllers\DataController;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        JsonResource::withoutWrapping();

        Response::macro('success', function () {
            return response()->json(['status' => 'success']);
        });

        Response::macro('failure', function (string $message = null) {
            return response()->json(array_filter(['status' => 'failure', 'message' => $message]), 400);
        });

        Response::macro('synchronize', function () {

            if (request()->lastUpdates()) {
                return response()->json((new DataController())->index());
            }

            return response()->json();
        });

        Request::macro('lastUpdates', function () {
            $date = request()->header('X-Last-Updates', '');

            try {
                if ($date && $date = Date::parse($date, 'UTC'))
                    return $date->setTimezone(config('app.timezone'));
            } catch (Exception $e) {

            }

            return null;

        });

        Request::macro('account', function () {
            if (($user = request()->user()) && $user instanceof HasAccounts)
                return $user->currentAccount();
            return null;
        });

        Request::macro('isAdminRoute', function () {
            return request()->routeIs('admin.*');
        });

        Request::macro('isDelegateRoute', function () {
            return request()->routeIs('delegate.*');
        });

        Request::macro('isClientRoute', function () {
            return request()->routeIs('client.*');
        });

        Relation::morphMap([
            'account' => Account::class,
            'admin' => Admin::class,
            'business' => Business::class,
            'client' => Client::class,
            'delegate' => Delegate::class,
            'governorate' => Governorate::class,
            'location' => Location::class,
            'order' => Order::class,
            'payment' => Payment::class,
            'plan' => Plan::class,
            'roster' => Roster::class,
            'statement' => Statement::class,
        ]);
    }
}
