<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Remotelywork\Installer\Repository\App;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if(App::dbConnectionCheck()){
            $themePath = realpath(__DIR__ . '/../../resources/views/frontend/');
            $theme = site_theme();

            // The active theme is checked first; the bundled "default" theme is
            // registered as a fallback so a theme only has to override the views
            // it actually customises. Without this, any page missing from an
            // active theme (task pages, for example) would fail to render.
            $paths = [$themePath . DIRECTORY_SEPARATOR . $theme];

            if ($theme !== 'default') {
                $paths[] = $themePath . DIRECTORY_SEPARATOR . 'default';
            }

            $this->loadViewsFrom($paths, 'frontend');
        }
    }
}
