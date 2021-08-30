<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/Users', [App\Http\Controllers\User\UserController::class, 'getUsers'])->name('Users');
Route::resource('user',\App\Http\Controllers\User\UserController::class);
Route::resource('role',\App\Http\Controllers\Role\RoleController::class);
Route::get('/search', [\App\Http\Controllers\User\UserController::class, 'GetUser'])->name('search');
Route::get('/audit', [\App\Http\Controllers\Audit\AuditController::class, 'showAudit'])->name('audit');
Route::get('profile/{id}/edit', [\App\Http\Controllers\User\UserController::class, 'editProfile'])->name('editProfile');
Route::any('profile/{id}', [\App\Http\Controllers\User\UserController::class, 'updateProfile'])->name('updateProfile');
//Route::any('/profile_store', [\App\Http\Controllers\Profile\ProfileController::class, 'storeProfile'])->name('store_profile');
