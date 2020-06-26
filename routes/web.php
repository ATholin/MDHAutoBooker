<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduledBookingController;
use App\Http\Controllers\UserBookingController;
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


Route::middleware('guest')->group(function () {
    Route::view('login', 'auth.login')->name('login');
    Route::view('register', 'auth.register')->name('register');
});

Route::view('password/reset', 'auth.passwords.email')->name('password.request');
Route::get('password/reset/{token}', 'Auth\PasswordResetController')->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('home');
    Route::post('/', [BookingController::class, 'book'])->name('bookings.book');

    Route::get('/bookings', [UserBookingController::class, 'index'])->name('user_bookings.index');
    Route::post('/bookings', [UserBookingController::class, 'book'])->name('user_bookings.book');
    Route::delete('/bookings', [UserBookingController::class, 'unBook'])->name('user_bookings.unBook');

    Route::get('/scheduled', [ScheduledBookingController::class, 'index'])->name('scheduled_booking.index');
    Route::post('/scheduled', [ScheduledBookingController::class, 'book'])->name('scheduled_booking.book');
    Route::get('/scheduled/{scheduled}/edit', [ScheduledBookingController::class, 'update'])->name('scheduled_booking.update');
    Route::post('/scheduled/next/{scheduled}', [ScheduledBookingController::class, 'addNextWeek'])->name('scheduled_booking.add_next_week');
    Route::post('/scheduled/{scheduled}/recurring', [ScheduledBookingController::class, 'setRecurring'])->name('scheduled_booking.set_recurring');
    Route::delete('/scheduled/{scheduled}', [ScheduledBookingController::class, 'destroy'])->name('scheduled_booking.destroy');

    Route::resource('credentials', 'KronoxCredentialsController');

    Route::resource('friends', 'FriendController');

    Route::get('/profile', 'ProfileController')->name('profile.index');

    Route::view('email/verify', 'auth.verify')->middleware('throttle:6,1')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'Auth\EmailVerificationController')->middleware('signed')->name('verification.verify');

    Route::post('logout', 'Auth\LogoutController')->name('logout');

    Route::view('password/confirm', 'auth.passwords.confirm')->name('password.confirm');
});
