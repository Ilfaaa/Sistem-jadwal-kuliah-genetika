<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     * Mengecek apakah user yang terautentikasi via Sanctum adalah admin (role_id = 1).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat mengakses resource ini.',
            ], 403);
        }

        return $next($request);
    }
}
