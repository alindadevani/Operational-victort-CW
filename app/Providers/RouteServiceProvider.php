<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Halaman utama setelah login (fallback), tidak dipakai langsung
     * karena kita punya logic redirect per-role di redirectByRole().
     */
    public const HOME = '/dashboard';

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = strtolower($request->input('email')).'|'.$request->ip();

            return Limit::perMinute(5)->by($throttleKey);
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
     * Menentukan URL redirect setelah login berdasarkan role user.
     * Dipanggil dari AuthenticatedSessionController & RedirectIfAuthenticated.
     */
    public static function redirectByRole(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'kepala_cabang' => route('kepala-cabang.dashboard'),
            'marketing' => route('marketing.dashboard'),
            default => '/',
        };
    }
}
