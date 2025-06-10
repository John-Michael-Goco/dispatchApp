<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\EmergencyController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BranchController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Auth::routes();

// Redirect /home based on user role
Route::get('/home', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.home');
    }
    return redirect()->route('user.home');
})->middleware('auth');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Emergency Management Routes
    Route::resource('emergencies', EmergencyController::class);
    // Unit Management Routes
    Route::resource('units', UnitController::class);
    // Service Management Routes
    Route::resource('services', ServiceController::class);
    // Branch Management Routes
    Route::resource('branches', BranchController::class);
});

// User Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Add other user routes here
});