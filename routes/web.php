<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::group([
    'controller' => ProfileController::class,
    'middleware' => 'auth',
    'as' => 'profile.',
], function () {

    Route::get('/user/{user}', 'index')->name('index');
    Route::post('/profile/update-cover', 'updateImage')->name('updateImage');
    Route::patch('/profile', 'update')->name('update');
    Route::delete('/profile', 'destroy')->name('destroy');
});

require __DIR__.'/auth.php';
