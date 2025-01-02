<?php

namespace Zacksmash\FortifyUI;

use Illuminate\Support\ServiceProvider;
use Zacksmash\FortifyUI\Commands\FortifyUICommand;

class FortifyUIServiceProvider extends ServiceProvider
{
    // foo=bar
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/resources/views' => base_path('resources/views'),
            ], 'fortify-ui-views');

            $this->publishes([
                __DIR__ . '/../stubs/app/Providers' => base_path('app/Providers'),
            ], 'fortify-ui-provider');

            $this->commands([
                FortifyUICommand::class,
            ]);
        }
    }
}
