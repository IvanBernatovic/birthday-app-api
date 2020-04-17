<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        Gate::define('manage-birthday', function ($user, $birthday) {
            return $user->id == $birthday->user_id;
        });

        Gate::define('manage-reminder', function ($user, $reminder) {
            if ($reminder->remindable_type == 2) {
                return $user->id == $reminder->remindable_id;
            }

            return false;
        });
    }
}
