<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function mapApiRoutes()
    {
        $version = 'v1';

        Route::prefix("$version/public/authentication")
            ->middleware(['api'])
            ->group(base_path("routes/api/$version/public/public-authentication.php"));

        Route::prefix("$version/private/authentication")
            ->middleware(['api', 'auth:api', 'verified', 'check-role', 'check-attempts', 'check-status']) // , 'check-permissions'
            ->group(base_path("routes/api/$version/private/private-authentication.php"));

        Route::prefix("$version/public/app")
            ->middleware(['api'])
            ->group(base_path("routes/api/$version/public/public-app.php"));

        Route::prefix("$version/private/app")
            ->middleware(['api', 'auth:api', 'verified', 'check-role', 'check-attempts', 'check-status']) //, 'check-permissions'
            ->group(base_path("routes/api/$version/private/private-app.php"));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10000);
        });
    }
}
