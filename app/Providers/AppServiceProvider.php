<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
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

        Validator::excludeUnvalidatedArrayKeys();

        Relation::enforceMorphMap([
            //
        ]);
    }
}
