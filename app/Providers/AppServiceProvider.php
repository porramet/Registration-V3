<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set Carbon locale globally to Thai
        Carbon::setLocale('th');
        setlocale(LC_TIME, 'th_TH.UTF-8');

        // Define months array for registrations_report view
        View::composer('backend.registrations_report', function ($view) {
            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthValue = sprintf('%04d-%02d', date('Y'), $m);
                $monthName = Carbon::create()->month($m)->locale('th')->isoFormat('MMMM');
                $months[] = ['value' => $monthValue, 'name' => $monthName];
            }
            $view->with('months', $months);
        });
    }
}
