<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
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

        if (!session('user_login') && !$isPublic) {
            return redirect('/login');
        } 
        return $next($request);
    }
}
