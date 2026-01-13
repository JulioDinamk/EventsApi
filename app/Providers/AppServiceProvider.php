<?php

namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Scramble::ignoreDefaultRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::registerApi('v1', [
            'info' => [
                'version' => env('API_VERSION', '0.0.1'),
                'description' => '',
            ],
        ])
            ->routes(function (Route $route) {
                return Str::startsWith($route->uri, 'v1/');
            })
            ->expose(
                ui: '/docs/v1',
                document: '/docs/v1.json'
            );
    }
}
