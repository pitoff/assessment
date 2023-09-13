<?php

namespace App\Providers;

use App\Http\Controllers\User\UserController;
use App\Interfaces\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        // Define the route macro
        Router::macro('softDeletes', function () {
            $this->get('/users-trashed', [UserController::class, 'trashed'])->name('users.trashed');
            $this->patch('/users-trashed/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
	        $this->delete('/users-trashed/delete/{id}', [UserController::class, 'forceDelete'])->name('users.delete');
        });
    }
}
