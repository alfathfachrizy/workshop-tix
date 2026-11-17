<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/my-registrations', function () {
        return view('dashboard');
    })->name('registrations.my');

    Route::get('/registrations/{registration}', function () {
        return view('dashboard');
    })->name('registrations.show');

    Route::post('/registrations/{registration}/check-status', function () {
        return back();
    })->name('registrations.check-status');

    Route::get('/tickets/{registration}', function () {
        return view('dashboard');
    })->name('tickets.show');
});

Route::middleware(['auth', 'role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/workshops', function () {
        return view('dashboard');
    })->name('workshops.index');

    Route::get('/registrations', function () {
        return view('dashboard');
    })->name('registrations.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
