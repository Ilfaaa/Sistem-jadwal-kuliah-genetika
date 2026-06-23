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
        
        $response = $next($request);
        
        if (method_exists($response, 'header')) {
            $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }
        
        return $response;
    }
}
