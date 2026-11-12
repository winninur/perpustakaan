<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Middleware fleksibel untuk membatasi akses berdasarkan role.
     * Penggunaan: Route::middleware('role:admin,pustakawan')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Role Anda (' . $userRole . ') tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
