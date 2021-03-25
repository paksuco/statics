<?php

namespace Paksuco\Statics;

use Illuminate\Support\ServiceProvider;

class StaticsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
        $this->handleMigrations();
        $this->handleViews();
        $this->handleTranslations();
        $this->handleRoutes();
        $this->handleResources();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind any implementations.
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function handleConfigs()
    {
        $configPath = __DIR__ . '/../config/paksuco-statics.php';

        $this->publishes([
            $configPath =>
            base_path('config/paksuco-statics.php'),
        ], "config");

        $this->mergeConfigFrom($configPath, 'paksuco-statics');
    }

    private function handleTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'paksuco-statics');
    }

    private function handleViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'paksuco-statics');

        $this->publishes([
            __DIR__ . '/../views' =>
            base_path('resources/views/vendor/paksuco-statics'),
        ], "views");
    }

    private function handleResources()
    {
        $this->publishes([
            __DIR__ . '/../resources/js/tinymce' =>
            base_path('public/assets/vendor/tinymce'),
        ], "pages-tinymce");
    }

    private function handleMigrations()
    {
        $this->publishes([
            __DIR__ . '/../migrations' =>
            base_path('database/migrations'),
        ], "migrations");
    }

    private function handleRoutes()
    {
        include __DIR__ . '/../routes/routes.php';
    }
}
