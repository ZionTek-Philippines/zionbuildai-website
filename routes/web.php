<?php

use App\Http\Controllers\DemoBookingController;
use App\Http\Controllers\DemoLeadController;
use App\Http\Controllers\SiteEventController;
use App\Http\Controllers\SitePreviewController;
use App\Http\Middleware\EnsureDemoIsEnabled;
use App\Http\Middleware\EnsureDemoIsUnlocked;
use App\Http\Middleware\RequireSitePreviewPassword;
use Illuminate\Support\Facades\Route;

// Private until the marketing launch: asks for a password (see RequireSitePreviewPassword).
Route::get('/', function () {
    return view('welcome');
})->middleware(RequireSitePreviewPassword::class)->name('home');

Route::post('/preview-access', [SitePreviewController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('site-preview.store');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'pages.terms-of-service')->name('terms-of-service');
Route::view('/data-security', 'pages.data-security')->name('data-security');

Route::post('/book-a-demo', [DemoBookingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('demo-booking.store');

// The "Try it!" live demo; DEMO_DISABLED=true turns all of it off.
Route::middleware(EnsureDemoIsEnabled::class)->group(function () {
    Route::view('/demo/how-it-works', 'demo.how-it-works')->name('demo.how-it-works');
    Route::view('/demo', 'demo.command-center')->name('demo.command-center');
    Route::view('/demo/field-reports', 'demo.field-reports')->name('demo.field-reports');
    Route::view('/demo/ai-project-monitoring', 'demo.ai-project-monitoring')->name('demo.ai-project-monitoring');

    // Deeper modules unlock after the visitor leaves their details.
    Route::middleware(EnsureDemoIsUnlocked::class)->group(function () {
        Route::view('/demo/finances', 'demo.finances')->name('demo.finances');
        Route::view('/demo/payroll', 'demo.payroll')->name('demo.payroll');
        Route::view('/demo/client-billing', 'demo.client-billing')->name('demo.client-billing');
    });

    Route::post('/demo/unlock', [DemoLeadController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('demo.unlock');
});

// Anonymous, cookie-less site analytics beacon (see resources/js/site-analytics.js).
Route::post('/zb/events', [SiteEventController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('site-events.store');
