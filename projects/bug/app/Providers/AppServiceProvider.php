<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*$this->app->booted(function () {
            if (class_exists(JWTAuth::class) && config("jwt.providers.storage")) {
                JWTAuth::setUserResolver(function () {
                    $payload = JWTAuth::getPayload();
                    $id = $payload->get("sub");
                    return (object)["id" => $id];
                });
            }
        });*/
    }
}
