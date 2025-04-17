<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\ClientMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(Router $router)
    {
        
        $router->aliasMiddleware('client', ClientMiddleware::class);
    }
}
