<?php

namespace Thatphon05\TrueWallet;

use Illuminate\Support\ServiceProvider;

/**
 * Class TrueWalletServiceProvider.
 */
class TrueWalletServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/truewallet.php' => config_path('truewallet.php'),
        ], 'truewallet');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('truewallet', function () {
            return new TrueWallet(config('truewallet.ssl'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['truewallet'];
    }
}
