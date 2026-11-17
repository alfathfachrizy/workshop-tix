<?php

use App\Http\Controllers\Panitia\DashboardController as PanitiaDashboardController;
use App\Http\Controllers\Panitia\WorkshopController as PanitiaWorkshopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicWorkshopController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicWorkshopController::class, 'index'])->name('home');
Route::get('/workshops/{slug}', [PublicWorkshopController::class, 'show'])->name('workshops.show');

Route::middleware('auth')->group(function () {
    Route::post('/workshops/{workshop}/register', [RegistrationController::class, 'store'])->name('workshops.register');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');

    Route::post('/registrations/{registration}/check-status', function () {
        return back();
    })->name('registrations.check-status');

    Route::get('/tickets/{registration}', function () {
        return view('dashboard');
    })->name('tickets.show');
});

Route::middleware(['auth', 'role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/', [PanitiaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/workshops/{workshop}/registrants', [PanitiaWorkshopController::class, 'registrants'])->name('workshops.registrants');
    Route::resource('workshops', PanitiaWorkshopController::class);

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
