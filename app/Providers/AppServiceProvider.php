<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
namespace App\Providers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
//use Laravel\Passport\Console\ClientCommand;
//use Laravel\Passport\Console\InstallCommand;
//use Laravel\Passport\Console\KeysCommand;
//use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
      public function boot()
    {
        Validator::extend('max_filename_length', function ($attribute, $value, $parameters, $validator) {
            $maxFilenameLength = (int) $parameters[0];
            return mb_strlen($value->getClientOriginalName()) <= $maxFilenameLength;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      //  Passport::routes();
      //  $this->commands([
      //      InstallCommand::class,
      //      ClientCommand::class,
       //     KeysCommand::class,
     //   ]);
    }
}
