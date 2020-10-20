<?php

namespace App\Providers;

use App\Models\Cuisine;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
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
            $params = $arguments;
            eval("\$params = [$arguments];");
            list($key, $default) = $params;
            return "<?php echo app_setting('$key', '$default'); ?>";
        });

        $totalNewCustomer = Cache::remember('_totalNewCustomer', 600, function () {
            return User::customer()->where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count();
        });
        View::share('_totalNewCustomer', $totalNewCustomer);

        $totalNewRestaurant = Cache::remember('_totalNewRestaurant', 600, function () {
            return Restaurant::where('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->count();
        });
        View::share('_totalNewRestaurant', $totalNewRestaurant);

        $totalNewCuisine = Cache::remember('_totalNewCuisine', 600, function () {
            return Cuisine::where('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->count();
        });
        View::share('_totalNewCuisine', $totalNewCuisine);
    }
}
