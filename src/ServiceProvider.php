<?php

namespace Maknz\Slack\Laravel;

use RuntimeException;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The actual provider.
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Instantiate the service provider.
     *
     * @param mixed $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->provider = $this->getProvider();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        return $this->provider->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        return $this->provider->register();
    }

    /**
     * Return the service provider for the particular Laravel version.
     *
     * @return mixed
     */
    private function getProvider()
    {
        $app = $this->app;

        $version = $app::VERSION;

        $versionSplit = explode('.', $version);

        $major = intval($versionSplit[0]);
        $minor = intval($versionSplit[1]);

        switch ($major) {
            case 4:
              return new ServiceProviderLaravel4($app);

            case 5:
                if ($minor >= 4) {
                    return new ServiceProviderLaravel5_4($app);
                } else {
                    return new ServiceProviderLaravel5($app);
                }

            default:
              throw new RuntimeException('Your version of Laravel is not supported');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['maknz.slack'];
    }
}
