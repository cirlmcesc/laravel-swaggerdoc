<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Providers;

use Illuminate\Support\ServiceProvider;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

class LaravelSwaggerdocServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;

    /**
     * config file path
     */
    const CONFIG_PATH = __DIR__."/../../config/swaggerdoc.php";

    /**
     * route file path
     */
    const ROUTE_PATH = __DIR__."/../../routes/swaggerdoc.php";

    /**
     * view file path
     */
    const VIEW_PATH = __DIR__."/../../views";

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, "swaggerdoc");

        $this->app->singleton(LaravelSwaggerdoc::class,
            function () {
                return new LaravelSwaggerdoc();
            });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([\Cirlmcesc\LaravelSwaggerdoc\Commands\InstallCommand::class]);
        }

        $this->publishes([self::CONFIG_PATH => config_path("swaggerdoc.php")], "swaggerdoc-config");

        $this->loadRoutesFrom(self::ROUTE_PATH);

        $this->loadViewsFrom(self::VIEW_PATH, 'swaggerdoc');

        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path("vendor/swaggerdoc"),
        ], "swaggerdoc-resources");
    }
}
