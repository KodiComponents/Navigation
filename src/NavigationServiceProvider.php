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

    /**
     * Get a list of files that should be compiled for the package.
     *
     * @return array
     */
    public static function compiles()
    {
        return [
            base_path('vendor\kodicomponents\navigation\src\Contracts\NavigationInterface.php'),
            base_path('vendor\kodicomponents\navigation\src\Contracts\PageInterface.php'),
            base_path('vendor\kodicomponents\navigation\src\Contracts\BadgeInterface.php'),
            base_path('vendor\kodicomponents\navigation\src\PageCollection.php'),
            base_path('vendor\kodicomponents\navigation\src\Badge.php'),
            base_path('vendor\kodicomponents\navigation\src\Page.php'),
        ];
    }
}