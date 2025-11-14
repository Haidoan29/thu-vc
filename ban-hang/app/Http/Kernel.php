<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Middleware mặc định
        
        // Middleware admin của bạn
        'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        'admin.guest' => \App\Http\Middleware\RedirectIfLoggedInAdmin::class,

    ];
    
}
