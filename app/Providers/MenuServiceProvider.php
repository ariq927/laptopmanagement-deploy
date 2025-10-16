<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
            $verticalMenuData = json_decode($verticalMenuJson);

            if (auth()->check() && auth()->user()->role === 'admin') {
                foreach ($verticalMenuData->menu as &$menu) {
                    if (isset($menu->slug) && $menu->slug === 'dashboard') {
                        $menu->url = '/admin/dashboard';
                    }
                }
            }

            $view->with('menuData', [$verticalMenuData]);
        });
    }
}
