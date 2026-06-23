<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckNotMahasiswaMiddleware
{
    /**
     * Handle an incoming request.
     * Blocks access for Mahasiswa users (role_id == 3).
     * Mahasiswa can only access: dashboard, hasil jadwal, profile, and logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_login = $request->session()->get('user_login');
        if ($user_login && $user_login->role_id == 3) {
            $publicRoutes = [
                'managekuliah',
                'managekuliah/keyword',
                'managekuliah/manageprodi',
                'managekuliah/manageprodi/keyword',
                'managekuliah/managematkul',
                'managekuliah/managematkul/keyword',
                'managekuliah/managedosen',
                'managekuliah/managedosen/keyword',
                'managekuliah/managekelas',
                'managekuliah/managekelas/keyword',
            ];

            $isPublic = false;
            foreach ($publicRoutes as $route) {
                if ($request->is($route)) {
                    $isPublic = true;
                    break;
                }
            }

            if (!$isPublic) {
                return redirect('/home/dashboard')->with('status', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
        return $next($request);
    }
}
