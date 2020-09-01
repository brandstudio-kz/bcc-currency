<?php
namespace BrandStudio\Currency;

use Illuminate\Support\ServiceProvider;
use BrandStudio\Currency\Console\Commands\UpdateCurrencies;

class CurrencyServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/currency.php', 'currency');

        if ($this->app->runningInConsole()) {
            $this->publish();
        }

        $this->commands([
            UpdateCurrencies::class,
        ]);

        if (config('currency.use_backpack')) {
            $this->loadRoutesFrom(__DIR__.'/routes/currency.php');
        }

    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'brandstudio');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'currency');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
            $this->publish();
        }
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/config/currency.php' => config_path('currency.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/resources/views/currency'      => resource_path('views/vendor/brandstudio/currency')
        ], 'views');

        $this->publishes([
            __DIR__.'/resources/lang'      => resource_path('lang/vendor/currency')
        ], 'lang');
    }

}
