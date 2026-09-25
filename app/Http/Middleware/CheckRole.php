<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware untuk membatasi akses route berdasarkan role user yang login.
     *
     * Cara pakai di route:
     *   Route::middleware(['auth', 'role:admin'])->group(...)
     *   Route::middleware(['auth', 'role:admin,kepala_cabang'])->group(...)
     *
     * @param  string  $roles  Daftar role yang diizinkan, dipisah koma.
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowedRoles = explode(',', $roles);

        // Jika belum login, lempar ke halaman login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Jika role user tidak termasuk yang diizinkan, tolak akses
        if (!in_array($request->user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
