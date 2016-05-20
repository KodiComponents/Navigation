<?php

namespace KodiComponents\Navigation;

use Illuminate\Support\ServiceProvider;
use KodiComponents\Navigation\Contracts\PageInterface;
use KodiComponents\Navigation\Contracts\BadgeInterface;

class NavigationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/navigation.php', 'navigation');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'navigation');

        $this->app->bind(PageInterface::class, Page::class);
        $this->app->bind(BadgeInterface::class, Badge::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/navigation.php' => config_path('navigation.php'),
        ], 'config');
    }
}