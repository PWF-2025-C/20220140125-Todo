<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menggunakan Tailwind untuk Paginator
        Paginator::useTailwind();

        // Mendefinisikan Gate 'admin' untuk memastikan user adalah admin
        Gate::define('admin', function ($user) {
            return $user->is_admin === true;
        });

        // Mengonfigurasi Scramble untuk route yang dimulai dengan 'api'
        Scramble::configure()->routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api');
        });
    }
}