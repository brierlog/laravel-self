<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        isDebug() && \DB::listen(function ($sql) {
            // @var QueryExecuted $sql
            \Log::info(sprintf('[%s ms] %s [%s]', $sql->time, $sql->sql, implode(',', $sql->bindings)));
        });
    }
}
