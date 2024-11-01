<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TwoFactorAuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','2fa'])->name('dashboard');

Route::middleware(['auth', 'verified', '2fa'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/two-factor-challenge', [TwoFactorAuthenticationController::class, 'show'])
    ->middleware(['auth'])
    ->name('two-factor.login');

Route::post('/two-factor-challenge', [TwoFactorAuthenticationController::class, 'verify'])
    ->middleware(['auth'])
    ->name('2fa.verify');

Route::get('/two-factor-recovery-challenge', [TwoFactorAuthenticationController::class, 'showRecoveryForm'])
    ->middleware(['auth'])
    ->name('two-factor.recovery.login');

Route::post('/two-factor-recovery-challenge', [TwoFactorAuthenticationController::class, 'verifyRecovery'])
    ->middleware(['auth'])
    ->name('2fa.verify.recovery');
require __DIR__ . '/auth.php';
