<?php

namespace ArnlInvoices\InvoiceManager;

use Illuminate\Support\ServiceProvider;

class InvoiceManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, etc.
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'invoicemanager');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        

        // Publish config, views, etc.
        $this->publishes([
            __DIR__.'/config/invoicemanager.php' => config_path('invoicemanager.php'),
        ], 'invoicemanager-config');
        $this->app->singleton('invoice-service', function () {
            return new \ArnlInvoices\InvoiceManager\Services\InvoiceService();
        });

        // Optional: publish the views
        // $this->publishes([
        //     __DIR__.'/resources/views' => resource_path('views/licensemanager'),
        // ], 'views');

        // Optional: Allow users to publish views to override
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/arnlinvoices/invoicemanager'),
        ], 'arnlinvoicemanager-views');
    }

    public function register()
    {
        // Register bindings, config merge, etc.
        if (file_exists(__DIR__.'/../config/invoicemanager.php')) {
            $this->mergeConfigFrom(
                __DIR__.'/config/invoicemanager.php', 'invoicemanager'
            );
        }
        require_once __DIR__.'/helpers.php';
    }


}
