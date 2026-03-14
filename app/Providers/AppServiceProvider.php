<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\FeedbackResend::class,
            ]);
        }
    }

    public function boot(): void
    {
        // Ganti cara memanggilnya jadi seperti ini (PASTI JALAN!)
        // Kita panggil lewat facade Gate, bukan langsung app()
        \Illuminate\Support\Facades\Gate::after(function ($user, $ability) {
            return $user->hasPermissionTo($ability) ? true : null;
        });
    }
}