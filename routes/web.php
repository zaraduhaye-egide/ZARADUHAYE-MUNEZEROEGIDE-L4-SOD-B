<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopkeeperController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;

// Remove any cached routes
Route::get('/clear-route', function() {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "All caches cleared successfully";
});

// Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [ShopkeeperController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ShopkeeperController::class, 'login']);
    
    Route::get('/register', [ShopkeeperController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [ShopkeeperController::class, 'register']);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Stock Management
    Route::resource('product-ins', ProductInController::class);
    Route::resource('product-outs', ProductOutController::class);
    
    // Reports
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [DashboardController::class, 'export'])->name('reports.export');
    
    // Logout
    Route::post('/logout', [ShopkeeperController::class, 'logout'])->name('logout');
}); 