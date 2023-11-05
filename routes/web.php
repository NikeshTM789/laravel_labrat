<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\{UserController, RoleController, PermissionController};
use App\Http\Controllers\Admin\Asset\{CategoryController, ProductController, TrashController};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin', 'middleware' => ['auth']], function() {
    Route::group(['as' => '.'], function() {
        Route::get('/', DashboardController::class)->name('home'); #invokable
        /*----------  Users  ----------*/
        Route::resource('user', UserController::class);
        Route::delete('user/delete/media/{user}', [UserController::class, 'deleteMedia'])->name('user.delete.media');
        Route::get('user/restore/{user}', [UserController::class, 'restore']);
        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);
        /*----------  Categories  ----------*/
        Route::resource('category', CategoryController::class);
        /*----------  Products  ----------*/
        Route::resource('product', ProductController::class);
        Route::post('product_image/{image_id?}', [ProductController::class, 'product_image'])->name('product.image');
        /*----------  Trashes  ----------*/
        Route::controller(TrashController::class)->group(function() {
            Route::get('trashed/{type}', 'index')->name('trashed.index');
            Route::get('trashed/restore/{type}/{uuid}', 'restore')->name('trashed.restore');
            Route::delete('trashed/delete/{type}/{uuid}', 'PDel')->name('trashed.delete');
        });
    });
});