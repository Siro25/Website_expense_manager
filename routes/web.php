<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

// Trang chủ cho khách
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes xác thực
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register')->middleware('guest');

    Route::post('/register', 'register')->name('register.post');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/user', 'user')->name('user.profile')->middleware('auth');
});

// Routes được bảo vệ bởi auth middleware
Route::middleware('auth')->group(function () {
    // Route cho tổng quan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes cho chi tiêu
    Route::get('/expenses/statistics', [ExpenseController::class, 'statistics'])->name('expenses.statistics');
    Route::resource('expenses', ExpenseController::class);

    // Routes cho thu nhập
    Route::resource('incomes', IncomeController::class);

    // Routes cho danh mục
    Route::get('/categories/{category}/expenses', [CategoryController::class, 'expenses'])->name('categories.expenses');
    Route::resource('categories', CategoryController::class);

    // Routes cho danh mục thu nhập
    Route::resource('income-categories', IncomeCategoryController::class);

    // Route cho thống kê
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    
    // Routes cho cài đặt tài khoản
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
});
