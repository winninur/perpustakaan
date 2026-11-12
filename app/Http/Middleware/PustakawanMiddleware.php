<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PustakawanMiddleware
{
    /**
     * Role 'admin' & 'pustakawan' boleh mengakses route ini.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'pustakawan'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk petugas perpustakaan.');
        }

        return $next($request);
    }
}
