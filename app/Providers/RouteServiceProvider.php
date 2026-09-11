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
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/user/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::middleware(['web', 'XSS', 'trans', 'translate'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'XSS', 'trans', 'translate'])
                ->group(base_path('routes/auth.php'));

            Route::middleware(['web', 'auth:admin', 'XSS', 'trans', 'isDemo', 'translate'])->prefix($this->adminPrefix())->name('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * The admin prefix lives in the database, but route registration runs during
     * boot. If the settings table is missing or unreachable, throwing here would
     * abort route registration entirely — no routes get names, so every later
     * route() call fails and the real error is buried. Fall back instead.
     *
     * @return string
     */
    protected function adminPrefix(): string
    {
        try {
            return setting('site_admin_prefix', 'global') ?: 'admin';
        } catch (\Throwable $e) {
            return 'admin';
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
