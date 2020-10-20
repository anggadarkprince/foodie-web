<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Courier;
use App\Models\Group;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\CourierPolicy;
use App\Policies\GroupPolicy;
use App\Policies\RestaurantPolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Group::class => GroupPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Restaurant::class => RestaurantPolicy::class,
        Courier::class => CourierPolicy::class,
        Setting::class => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-dashboard', function ($user) {
            return $user->type == User::TYPE_MANAGEMENT
                ? Response::allow()
                : Response::deny('You must be a management member.');
        });

        Passport::routes(function ($router) {
            $router->forAccessTokens();
            $router->forPersonalAccessTokens();
            $router->forTransientTokens();
        });

        Passport::tokensExpireIn(now()->addHours(2));

        Passport::refreshTokensExpireIn(now()->addDays(90));

        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
