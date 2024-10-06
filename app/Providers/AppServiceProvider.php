<?php

namespace App\Providers;
use Illuminate\Support\Facades\Event;
use App\Events\TaskUpdated;
use App\Listeners\SendTaskUpdateNotification;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();
$this->app->bind(TaskRepositoryInterface::class,TaskRepository::class);
        $this->app->bind(TaskService::class, function($app) {
            return new TaskService($app->make(TaskRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            TaskUpdated::class,
            SendTaskUpdateNotification::class,
        );
    }
}
