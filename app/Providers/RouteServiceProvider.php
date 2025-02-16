<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
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
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::macro('softDeletes', function (string $base, string $controller) {
            Route::group(['middleware' => 'auth'], function () use ($base, $controller) {
                Route::get("$base/trashed", [$controller, 'trashed'])->name('user.trashed');
                Route::patch("/$base/{user}/restore", [$controller, 'restore'])
                    ->withTrashed()
                    ->name('user.restore');
                Route::delete("/$base/{user}/delete", [$controller, 'delete'])
                    ->withTrashed()
                    ->name('user.delete');
            });
        });
    }
}
