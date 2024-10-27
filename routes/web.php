<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth'])->name('home');

Route::group([
    'controller' => ProfileController::class,
    'middleware' => 'auth',
    'as' => 'profile.',
], function () {
    Route::get('user/{user}', 'index')->name('index');
    Route::patch('profile', 'update')->name('update');
    Route::delete('profile', 'destroy')->name('destroy');
    Route::post('profile/update-image', 'updateImage')->name('updateImage');
});

Route::group([
    'controller' => PostController::class,
    'middleware' => 'auth',
    'as' => 'posts.',
    'prefix' => 'posts'
], function () {
    Route::post('', 'store')->name('store');
    Route::put('{post}', 'update')->name('update');
    Route::delete('{post}', 'destroy')->name('destroy');

    Route::get('attachments/{attachment}/download', 'download')->name('attachments.download');
});

Route::group([
    'controller' => PostCommentController::class,
    'middleware' => 'auth',
    'as' => 'posts.comments.',
    'prefix' => 'comments'
], function () {
    Route::post('{post}', 'store')->name('store');
    Route::put('{comment}', 'update')->name('update');
    Route::delete('{comment}', 'destroy')->name('destroy');
});

Route::group([
    'controller' => ReactionController::class,
    'middleware' => 'auth',
    'as' => 'reactions.',
    'prefix' => 'reactions'
], function () {
    Route::post('{post}', 'postReaction')->name('posts.store');
    Route::post('{comment}', 'commentReaction')->name('comments.store');
});

Route::group([
    'controller' => GroupController::class,
    'middleware' => 'auth',
    'as' => 'groups.',
    'prefix' => 'groups'
], function () {
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::patch('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');
    Route::post('{group}/invite', 'invite')->name('invite');
    Route::post('{group}/update-image', 'updateImage')->name('updateImage');
    Route::get('{token}/accept-invite', 'acceptInvite')->withoutMiddleware('auth')->name('accept-invite');
    Route::post('{group}/join', 'join')->name('join');
});

require __DIR__ . '/auth.php';
