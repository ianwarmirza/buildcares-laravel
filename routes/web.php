<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

use App\Http\Controllers\TeamController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/team', [TeamController::class, 'index'])->name('team.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin auth routes (guest)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('portfolio/quick-upload', [AdminPortfolioController::class, 'quickUpload'])->name('portfolio.quickUpload');
        Route::match(['post', 'delete', 'patch'], 'portfolio/bulk-delete', [AdminPortfolioController::class, 'bulkDestroy'])->name('portfolio.bulkDestroy');
        Route::resource('portfolio', AdminPortfolioController::class)->except(['show']);
        Route::resource('services', AdminServiceController::class)->except(['show']);

        Route::match(['post', 'delete', 'patch'], 'team/bulk-delete', [AdminTeamController::class, 'bulkDestroy'])->name('team.bulkDestroy');
        Route::resource('team', AdminTeamController::class)->except(['show']);

        Route::match(['post', 'delete', 'patch'], 'ongoing-projects/bulk-delete', [\App\Http\Controllers\Admin\OngoingProjectController::class, 'bulkDestroy'])->name('ongoing-projects.bulkDestroy');
        Route::resource('ongoing-projects', \App\Http\Controllers\Admin\OngoingProjectController::class)->except(['show']);

        Route::get('messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::match(['post', 'delete', 'patch'], 'messages/bulk-delete', [AdminMessageController::class, 'bulkDestroy'])->name('messages.bulkDestroy');
        Route::delete('messages/delete-all', [AdminMessageController::class, 'deleteAll'])->name('messages.deleteAll');
        Route::get('messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/read', [AdminMessageController::class, 'toggleRead'])->name('messages.toggleRead');
        Route::delete('messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('settings/profile', [AdminSettingController::class, 'updateProfile'])->name('settings.profile');
        Route::put('settings/site', [AdminSettingController::class, 'updateSiteInfo'])->name('settings.site');
    });
});

// Fallback direct storage file server route
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');
