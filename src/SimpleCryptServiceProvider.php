<?php

namespace Hamidin\SimpleCrypt;

use Composer\InstalledVersions;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Collection;
use Hamidin\SimpleCrypt\Console\SimpleCryptCreateYmlCommand;
use Hamidin\SimpleCrypt\Console\SimpleCryptWatchCommand;

class SimpleCryptServiceProvider extends BaseServiceProvider
{

    /**
     * The prefix to use for register/load the package resources.
     *
     * @var string
     */
    protected $pkgPrefix = 'simplecrypt';

    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/simplecrypt.php',
            'simplecrypt'
        );
    }

    
    /**
     * Bootstrap the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->offerPublishing();
        $this->registerCommands();
        $this->registerAbout();
    }

    
    /**
     * Register the artisan commands of the package.
     *
     * @return void
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SimpleCryptWatchCommand::class,
                SimpleCryptCreateYmlCommand::class,
            ]);
        }
    }

    
    /**
     * Get the absolute path to some package resource.
     *
     * @param  string  $path  The relative path to the resource
     * @return string
     */
    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    protected function registerAbout(): void
    {
        if (! class_exists(InstalledVersions::class) || ! class_exists(AboutCommand::class)) {
            return;
        }

        // array format: 'Display Text' => 'boolean-config-key name'
        $features = [
            'Create-Yaml-File' => 'create_yaml',
            'Create-Shell-File' => 'create_shell',
        ];
        $config = $this->app['config'];

        AboutCommand::add('Simple Crypt', static fn () => [
            'Features Enabled' => collect($features)
                ->filter(fn (string $feature, string $name): bool => $config->get("simplecrypt.{$feature}"))
                ->keys()
                ->whenEmpty(fn (Collection $collection) => $collection->push('Default'))
                ->join(', '),
            'Version' => InstalledVersions::getPrettyVersion('hamidin/simple-crypt'),
        ]);
    }

    protected function offerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../config/simplecrypt.php' => config_path('simplecrypt.php'),
        ], 'simplecrypt-config');

    }
}