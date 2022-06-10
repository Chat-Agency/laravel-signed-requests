<?php
namespace ChatAgency\LaravelSignedRequests;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use ChatAgency\LaravelSignedRequests\Http\Middleware\ValidateSignedRequest;

class LaravelSignedRequestsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
            __DIR__ . '/../config/signed-requests.php' => config_path('signed-requests.php'),
        ], 'laravel-signed-requests');

            $this->publishes(
                [__DIR__ . '/Http/Middleware/ValidateSignedRequest.php' => app_path('Http/Middleware/ValidateSignedRequest.php')],
                'laravel-signed-requests'
            );
        }
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('signed-requests', ValidateSignedRequest::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/signed-requests.php',
            'signed-requests'
        );
    }
}
