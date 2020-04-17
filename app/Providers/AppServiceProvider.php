<?php

namespace App\Providers;

use App\Models\Birthday;
use App\Models\Reminder;
use App\Observers\BirthdayObserver;
use App\Observers\RemindersObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        if ($this->app->environment() !== 'production') {
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
        Relation::morphMap([
            1 => Birthday::class,
            2 => User::class,
        ]);

        User::observe(UserObserver::class);
        Birthday::observe(BirthdayObserver::class);
        Reminder::observe(RemindersObserver::class);
    }
}
