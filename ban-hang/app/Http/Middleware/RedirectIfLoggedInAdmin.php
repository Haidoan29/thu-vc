<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIfLoggedInAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('user_id') && Session::get('user_role') === 'admin') {
            return redirect('/admin/products');
        }

        return $next($request);
    }
}
