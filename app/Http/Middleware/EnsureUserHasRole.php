<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        
        // 2. Cek apakah role user yang login ada di dalam daftar role yang diizinkan ($roles)
        // $user->role akan menghasilkan nilai string 'admin', 'vendor', atau 'customer'
        if (!in_array($user->role, $roles)) {
            // Jika role tidak diizinkan, kembalikan response 403 Forbidden
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin yang diperlukan.');
        }

        // 3. Lanjutkan ke request berikutnya jika diizinkan
        return $next($request);
    }
}
