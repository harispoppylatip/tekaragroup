<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Route::resourceVerbs([
            'create' => 'tambah',
            'edit' => 'ubah',
        ]);

        View::composer(['components.layout', 'components.panel-layout', 'home', 'posts.index', 'posts.show'], function ($view): void {
            $view->with('site', Setting::allValues());
        });
    }
}
