<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopkeeperController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [ShopkeeperController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ShopkeeperController::class, 'login'])->name('login');
    
    Route::get('/register', [ShopkeeperController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [ShopkeeperController::class, 'register'])->name('register');
});

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Stock Management
    Route::get('/stock/in', [ProductInController::class, 'index'])->name('stock.in.index');
    Route::post('/stock/in', [ProductInController::class, 'store'])->name('stock.in.store');
    Route::get('/stock/in/{stockIn}/edit', [ProductInController::class, 'edit'])->name('stock.in.edit');
    Route::put('/stock/in/{stockIn}', [ProductInController::class, 'update'])->name('stock.in.update');
    Route::delete('/stock/in/{stockIn}', [ProductInController::class, 'destroy'])->name('stock.in.destroy');
    
    // Stock Out Management
    Route::get('/stock/out', [ProductOutController::class, 'index'])->name('product-outs.index');
    Route::post('/stock/out', [ProductOutController::class, 'store'])->name('product-outs.store');
    Route::get('/stock/out/{stockOut}/edit', [ProductOutController::class, 'edit'])->name('product-outs.edit');
    Route::put('/stock/out/{stockOut}', [ProductOutController::class, 'update'])->name('product-outs.update');
    Route::delete('/stock/out/{stockOut}', [ProductOutController::class, 'destroy'])->name('product-outs.destroy');
    
    // Reports
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/stock-in', [ReportController::class, 'stockIn'])->name('reports.stock-in');
    Route::get('/reports/stock-out', [ReportController::class, 'stockOut'])->name('reports.stock-out');
    Route::get('/reports/current-stock', [ReportController::class, 'currentStock'])->name('reports.current-stock');
    Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
    Route::get('/reports/export', [DashboardController::class, 'export'])->name('reports.export');
});

// Logout Route
Route::post('/logout', [ShopkeeperController::class, 'logout'])->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
