<?php

namespace MarcusGaius\ApiReports;

use Illuminate\Support\ServiceProvider;

class ApiReportsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerResources();
    }

    public function register()
    {
        
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes');
    }
}
