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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Get the path to redirect to after authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public static function getHomeRoute($request)
    {
        if ($request->user()) {
            return route('dashboard');
        }
        return '/';
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Get the path the user should be redirected to.
     *
     * @param  mixed  $user
     * @return string
     */
    protected function redirectTo($user)
    {
        if ($user->role === 'client') {
            return route('dashboard');
        } elseif ($user->role === 'craftsman') {
            return route('dashboard');
        } elseif ($user->role === 'admin') {
            return route('admin.dashboard');
        }

        return route('dashboard');
    }
}
