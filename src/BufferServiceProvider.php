<?php

namespace Mbarwick83\Buffer;

use Illuminate\Support\ServiceProvider;

class BufferServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/buffer.php' => config_path('buffer.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Mbarwick83\Buffer\Buffer', function($app) {
            return new Buffer($app);
        });
    }
}