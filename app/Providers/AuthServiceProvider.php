<?php

namespace App\Providers;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\NotificationServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('config-authorization', function(User $user){
            return $user->permission == 1;
        });

        Gate::define('manager-establishment-regionalManager-links-caller-create-reports', function(User $user){
            return $user->permission == 2 || $user->permission == 1;
        });

    }
}
