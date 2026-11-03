<?php

use App\Mail\WeeklyAnalyticsReport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:weekly-report', function () {
    Mail::to(config('services.analytics.weekly_report_to'))->send(new WeeklyAnalyticsReport);

    $this->info('Weekly analytics report sent to '.config('services.analytics.weekly_report_to').'.');
})->purpose('Email the last 7 days of site analytics');

Schedule::command('model:prune')->daily();
Schedule::command('analytics:weekly-report')->weeklyOn(1, '8:00')->timezone('Asia/Manila');
