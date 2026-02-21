<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\Team\TeamMemberController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn() => redirect()->route('dashboard'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetController::class, 'forgotCreate'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotStore'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetCreate'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetStore'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Email verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')->name('verification.send');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/teams/{team}/switch', [DashboardController::class, 'switchTeam'])->name('teams.switch');

    // Teams
    Route::resource('teams', TeamController::class);

    // Team Members
    Route::prefix('teams/{team}/members')->name('teams.members.')->group(function () {
        Route::post('/', [TeamMemberController::class, 'store'])->name('store');
        Route::patch('/{user}/role', [TeamMemberController::class, 'updateRole'])->name('update-role');
        Route::delete('/{user}', [TeamMemberController::class, 'destroy'])->name('destroy');
    });

    // Tasks (nested under teams)
    Route::prefix('teams/{team}/tasks')->name('teams.tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->name('update-status');
    });

    // Activity Log
    Route::get('/teams/{team}/activity', [ActivityController::class, 'index'])->name('teams.activity');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
