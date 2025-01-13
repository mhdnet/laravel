<?php

namespace App\Providers;

use App\Models\{Payment, Roster, Statement};
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            if($request->isAdminRoute())
                return  Limit::none();

            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function (Request $request) {

            if (!config('app.api') || config('app.api') == config('app.url'))
            {
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));
            }
            else
            {

                Route::middleware('api')
                    ->domain(config('app.api'))
                    ->group(base_path('routes/api.php'));

                $url = $request->host();

                Route::middleware('web')
                    ->domain(str_replace("api.", "", $url))

                    ->group(base_path('routes/web.php'));
            }


        });


        Route::bind('payment', function (string $id) {
            return Payment::findOrFail($id);
        });

        Route::bind('statement', function (string $id) {
            return Statement::findOrFail($id);
        });

    }
}
