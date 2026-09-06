<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia\Inertia::render('Dashboard', [
            'total_users' => \App\Models\User::count(),
        ]);
    })->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/settings.php';
