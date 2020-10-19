<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('numeric', function ($number) {
            return "<?php echo numeric($number); ?>";
        });
        Blade::directive('setting', function ($arguments) {
            eval("\$params = [$arguments];");
            list($key, $default) = $params;
            return "<?php echo app_setting('$key', '$default'); ?>";
        });
    }
}
