<?php

namespace Bulbalara\CoreConfigOa;

use Illuminate\Support\ServiceProvider;

class ConfigOaServiceProvider extends ServiceProvider
{
    protected array $commands = [
        Console\InstallCommand::class
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bl.config');

        CoreConfigExtension::boot();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'bl.config');

        $this->app->singleton('bl.config.config_oa', function () {
            return new \Bulbalara\CoreConfigOa\Facades\Implement\CoreConfigOa;
        });

        $this->commands($this->commands);
    }
}
