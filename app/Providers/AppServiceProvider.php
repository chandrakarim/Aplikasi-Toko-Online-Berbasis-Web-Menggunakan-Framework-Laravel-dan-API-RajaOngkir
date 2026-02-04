<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Alamat_toko;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        View::share('user', User::select('id', 'name', 'email', 'no_tlp')->find(1));
        View::share('alamat_toko', Alamat_toko::select('id', 'detail')->find(1));
    }
}
