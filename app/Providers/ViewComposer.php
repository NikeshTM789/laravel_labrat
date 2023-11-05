<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class ViewComposer extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $admin = 'admin.';
        view()->composer([
            $admin.'users.form'
        ], function($view){
            $view->with('roles', Role::select(['id','name'])->get());
        });
    }
}
