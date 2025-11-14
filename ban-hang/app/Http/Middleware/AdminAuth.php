<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
   public function handle($request, Closure $next)
{
    if (!Session::has('user_id')) {
        return redirect('/admin/login');
    }

    // Kiểm tra role
    if (Session::get('user_role') !== 'admin') {
        abort(403, 'Bạn không có quyền truy cập');
    }

    return $next($request);
}

}
