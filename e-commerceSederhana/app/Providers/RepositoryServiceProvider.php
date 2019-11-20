<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Categories\CategoryRepository;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Repositories\Orders\OrderRepository;
use App\Repositories\Orders\OrderRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class,
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class,
        );
    }
}