<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setLocale('zh'); //中文
        \App\Models\Topic::observe(\App\Observers\TopicObserver::class); // 摘要
        \App\Models\Reply::observe(\App\Observers\ReplyObserver::class); //回复
        \App\Models\Link::observe(\App\Observers\LinkObserver::class); //外链


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if (app()->isLocal()) {

            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }

    }
}
