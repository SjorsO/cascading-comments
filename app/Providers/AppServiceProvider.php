<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Model::preventLazyLoading(! app()->environment('production'));

        Model::unguard();

        Relation::enforceMorphMap([
            1 => User::class,
        ]);
    }
}
