<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::check()) { // Người dùng đã login
                $user = Auth::user();

                // Nếu admin thì redirect admin, nếu user bình thường thì dashboard
                if ($user->role === 'admin') {
                    return redirect('/admin/products');
                } else {
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
