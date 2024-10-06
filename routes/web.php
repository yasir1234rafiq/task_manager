<?php

use
    Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOrganizer;
use App\Http\Middleware\isAttendee;
use App\Http\Controllers\BookingController;

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

// Public Routes

Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('admin-login', [UserController::class, 'adminLogin'])->name('login.custom');
Route::get('registration', [UserController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [UserController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [UserController::class, 'signOut'])->name('signout');

Route::middleware('auth')->group(function () {
Route::get('home', [TaskController::class, 'task'])->name('home');
Route::get('/tasks/{id}', [TaskController::class, 'taskshow'])->name('task.show');
Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('task.complete');
Route::post('/tasks/{id}/feedback', [TaskController::class, 'feedback'])->name('task.feedback');
Route::resource('tasks', TaskController::class);

});
