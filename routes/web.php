<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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

Route::group([
    'controller' => PostController::class,
    'middleware' => 'auth',
    'as' => 'post.',
    'prefix' => 'post'
], function () {
    //Route::get('/post/{post}', 'index')->name('index');
    Route::post('create', 'store')->name('create');
    Route::put('{post}', 'update')->name('update');
    Route::delete('{post}', 'destroy')->name('destroy');
    Route::get('attachment/{attachment}/download', 'download')->name('attachment.download');
    Route::post('{post}/react', 'postReaction')->name('reaction.store');
    Route::post('{post}/comment', 'createComment')->name('comment.store');
    Route::put('{comment}/comment', 'updateComment')->name('comment.update');
    Route::delete('{comment}/comment', 'deleteComment')->name('comment.delete');
});

require __DIR__.'/auth.php';
