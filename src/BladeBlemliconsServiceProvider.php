<?php

declare(strict_types=1);

namespace BladeUI\Blemlicons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeBlemliconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-blemlicons', []);

            $factory->add('Blemlicons', array_merge(['path' => __DIR__.'/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/blade-blemlicons.php', 'blade-blemlicons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/blade-blemlicons'),
            ], 'blade-blemlicons');

            $this->publishes([
                __DIR__.'/../config/blade-blemlicons.php' => $this->app->configPath('blade-blemlicons.php'),
            ], 'blade-blemlicons-config');
        }
    }
}
