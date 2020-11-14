<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\ActivityRepositoryInterface',
            'App\Repositories\ActivityRepository'
        );
        $this->app->bind(
            'App\Repositories\AdmissionRepositoryInterface',
            'App\Repositories\AdmissionRepository'
        );
        $this->app->bind(
            'App\Repositories\LoginRepositoryInterface',
            'App\Repositories\LoginRepository'
        );
        $this->app->bind(
            'App\Repositories\AnalyzeRepositoryInterface',
            'App\Repositories\AnalyzeRepository'
        );

    }
}
