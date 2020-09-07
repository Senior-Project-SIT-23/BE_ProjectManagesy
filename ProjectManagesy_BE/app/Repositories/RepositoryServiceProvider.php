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
        // $this->app->bind(
        //     'App\Repositories\...RepositoryInterface',
        //     'App\Repositories\...Repository'
        // );

}
}