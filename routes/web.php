<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentAvailabilityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminAgentController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

/*
|--------------------------------------------------------------------------
| Public / Auth routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Agent-facing routes (logged-in agent only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [AgentController::class, 'show'])->name('agent.show')->middleware('agent');
    Route::get('/profile/edit', [AgentController::class, 'edit'])->name('agent.edit')->middleware('agent');
    Route::put('/profile', [AgentController::class, 'update'])->name('agent.update')->middleware('agent');

    Route::get('/availability', [AgentAvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AgentAvailabilityController::class, 'store'])->name('availability.store');
    Route::put('/availability/{agentAvailability}', [AgentAvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{agentAvailability}', [AgentAvailabilityController::class, 'destroy'])->name('availability.destroy');

    Route::resource('clients', ClientController::class)->names('agent.clients');

    Route::get('/properties/filter', [PropertyController::class, 'filter'])->name('agent.properties.filter');
    Route::resource('properties', PropertyController::class)->names('agent.properties');
    Route::patch('/properties/{property}/favorite', [PropertyController::class, 'toggleFavorite'])->name('agent.properties.favorite');
    Route::patch('/properties/{property}/status', [PropertyController::class, 'updateStatus'])->name('agent.properties.status');

    Route::get('/properties/{property}/photos', [PropertyController::class, 'photosIndex'])->name('agent.properties.photos.index');
    Route::post('/properties/{property}/photos', [PropertyController::class, 'storePhotos'])->name('agent.properties.photos.store');
    Route::patch('/properties/{property}/photos/reorder', [PropertyController::class, 'reorderPhotos'])->name('agent.properties.photos.reorder');
    Route::patch('/properties/{property}/photos/{photoId}/primary', [PropertyController::class, 'setPrimaryPhoto'])->name('agent.properties.photos.primary');
    Route::delete('/properties/{property}/photos/{photoId}', [PropertyController::class, 'destroyPhoto'])->name('agent.properties.photos.destroy');

    Route::get('/appointments/calendar', [CalendarController::class, 'index'])->name('agent.appointments.calendar');

    Route::resource('appointments', AppointmentController::class)->names('agent.appointments');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

});

/*
|--------------------------------------------------------------------------
| Admin-facing routes (admin only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('agents', AdminAgentController::class);
    Route::resource('clients', AdminClientController::class);
    Route::resource('properties', PropertyController::class);

    // Add these lines for the Settings page
    Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});