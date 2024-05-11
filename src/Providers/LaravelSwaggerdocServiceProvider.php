<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Providers;

use Illuminate\Support\ServiceProvider;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;
use Cirlmcesc\LaravelSwaggerdoc\Commands\InstallCommand;
use Cirlmcesc\LaravelSwaggerdoc\Commands\GenerateCommand;
use Cirlmcesc\LaravelSwaggerdoc\Commands\MakeCommand;

class LaravelSwaggerdocServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        /** Merge Settings */
        $this->mergeConfigFrom(__DIR__."/../../config/swaggerdoc.php", "swaggerdoc");

        /** Registering singleton LaravelSwaggerdoc objects */
        if ($this->app->environment('local')) {
            $this->app->singleton(LaravelSwaggerdoc::class, fn() => new LaravelSwaggerdoc());
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this
            ->registerCommandInConsole()
            ->publishesResources()
            ->loadResources()
            ->setFilesystemDisk();
    }

    /**
     * Registering commands in the command line runtime environment
     *
     * @return self
     */
    private function registerCommandInConsole(): self
    {
        if ($this->app->runningInConsole() == true) {
            $this->commands([
                InstallCommand::class, 
                GenerateCommand::class,
                MakeCommand::class,
            ]);
        }

        return $this;
    }

    /**
     * load resources function
     *
     * @return self
     */
    private function loadResources(): self
    {
        // $this->loadRoutesFrom(self::ROUTE_PATH);

        // $this->loadViewsFrom(self::VIEW_PATH, 'swaggerdoc');

        $this->loadTranslationsFrom(__DIR__."/../../lang", "swaggerdoc");

        return $this;
    }

    /**
     * publishes resources function
     *
     * @return self
     */
    private function publishesResources(): self
    {
        // const ROUTE_PATH = __DIR__."/../../routes/swaggerdoc.php";
        // const VIEW_PATH = __DIR__."/../../views";

        // $this->publishes([self::CONFIG_PATH => config_path("swaggerdoc.php")], "swaggerdoc-config");

        // $this->publishes([
        //     __DIR__ . '/../../resources/assets' => public_path("vendor/swaggerdoc"),
        // ], "swaggerdoc-resources");

        return $this;
    }

    /**
     * set filesystem disk
     *
     * @return self
     */
    private function setFilesystemDisk(): self
    {
        config([
            'filesystems.disks.swaggerdoc' => [
                'driver' => 'local',
                'root' => base_path(),
                'throw' => false,
            ],
        ]);

        return $this;
    }
}
