<?php

namespace Hamidin\SimpleCrypt;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        // Bind a singleton instance of the SimpleCrypt class into the service
        // container.

        // $this->app->singleton(SimpleCrypt::class, function () {
        //     return new SimpleCrypt(config('simplecrypt.filters', []));
        // });
    }

    
    /**
     * Bootstrap the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
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
}