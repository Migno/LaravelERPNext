<?php

namespace STREETEC\LaravelERPNext;

use Illuminate\Support\ServiceProvider;
use STREETEC\LaravelERPNext\Configuration\ConfigurationInterface;
use STREETEC\LaravelERPNext\Configuration\LaravelConfiguration;

class ERPNextServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/erpnext.php', 'erpnext');
        $this->publishes([
            __DIR__ . '/../config/erpnext.php' => config_path('erpnext.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind(ConfigurationInterface::class, function() {
            return new LaravelConfiguration();
        });
        $this->app->bind(ERPNextClient::class, function(){
            return new ERPNextClient($this->app->make(ConfigurationInterface::class));
        });
        $this->app->alias(ERPNextClient::class, 'erpnext');
    }
}
