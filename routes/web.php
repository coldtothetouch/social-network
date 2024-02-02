<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::get('/user/{user}', [ProfileController::class, 'index'])->name('profile');

Route::group([
    'controller' => ProfileController::class,
    'middleware' => 'auth',
    'as' => 'profile.',
], function () {
    Route::post('/profile/update-cover', [ProfileController::class, 'updateImage'])->name('updateImage');
    /*Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');*/
});

require __DIR__.'/auth.php';
