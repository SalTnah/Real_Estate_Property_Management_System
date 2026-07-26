<?php

use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Agent\ClientController as AgentClientController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Agent\PropertyController as AgentPropertyController;
use App\Http\Controllers\AgentAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Shared authenticated routes (single-role features, no role branching)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Agent availability
    Route::get('/availability', [AgentAvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AgentAvailabilityController::class, 'store'])->name('availability.store');
    Route::put('/availability/{agentAvailability}', [AgentAvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{agentAvailability}', [AgentAvailabilityController::class, 'destroy'])->name('availability.destroy');

    // Appointments
    Route::resource('appointments', AppointmentController::class);

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'store'])->name('search.store');
    Route::delete('/search/{search}', [SearchController::class, 'destroy'])->name('search.destroy');

    // Saved searches
    Route::get('/saved-searches', [SavedSearchController::class, 'index'])->name('saved-searches.index');
    Route::post('/saved-searches', [SavedSearchController::class, 'store'])->name('saved-searches.store');
    Route::put('/saved-searches/{savedSearch}', [SavedSearchController::class, 'update'])->name('saved-searches.update');
    Route::delete('/saved-searches/{savedSearch}', [SavedSearchController::class, 'destroy'])->name('saved-searches.destroy');

});

/*
|--------------------------------------------------------------------------
| Agent-facing routes (role-segmented — Agent\* controllers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'agent'])->name('agent.')->group(function () {

    // Agent's own profile (singular resource — no index/create/destroy)
    Route::get('/profile', [AgentProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AgentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AgentProfileController::class, 'update'])->name('profile.update');

    // Properties (agent-owned only — AgentPropertyController scopes to auth()->user()->agent)
    Route::get('/properties/filter', [AgentPropertyController::class, 'filter'])->name('properties.filter');
    Route::resource('properties', AgentPropertyController::class);
    Route::patch('/properties/{property}/favorite', [AgentPropertyController::class, 'toggleFavorite'])->name('properties.favorite');
    Route::patch('/properties/{property}/status', [AgentPropertyController::class, 'updateStatus'])->name('properties.status');

    Route::get('/properties/{property}/photos', [AgentPropertyController::class, 'photosIndex'])->name('properties.photos.index');
    Route::post('/properties/{property}/photos', [AgentPropertyController::class, 'storePhotos'])->name('properties.photos.store');
    Route::patch('/properties/{property}/photos/reorder', [AgentPropertyController::class, 'reorderPhotos'])->name('properties.photos.reorder');
    Route::patch('/properties/{property}/photos/{photoId}/primary', [AgentPropertyController::class, 'setPrimaryPhoto'])->name('properties.photos.primary');
    Route::delete('/properties/{property}/photos/{photoId}', [AgentPropertyController::class, 'destroyPhoto'])->name('properties.photos.destroy');

    // Clients (agent's own book of business)
    Route::resource('clients', AgentClientController::class);

});

/*
|--------------------------------------------------------------------------
| Admin-facing routes (role-segmented — Admin\* controllers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Agent account management (unchanged — already correctly namespaced)
    Route::resource('agents', AdminAgentController::class);

    // Admin's own account settings (name/email/password on the User model)
    Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // Properties (global — AdminPropertyController scopes from Property::query() directly)
    // No favorite-toggle for admin: it's a personal agent bookmark, not an oversight feature.
    Route::get('/properties/filter', [AdminPropertyController::class, 'filter'])->name('properties.filter');
    Route::resource('properties', AdminPropertyController::class);
    Route::patch('/properties/{property}/status', [AdminPropertyController::class, 'updateStatus'])->name('properties.status');

    Route::get('/properties/{property}/photos', [AdminPropertyController::class, 'photosIndex'])->name('properties.photos.index');
    Route::post('/properties/{property}/photos', [AdminPropertyController::class, 'storePhotos'])->name('properties.photos.store');
    Route::patch('/properties/{property}/photos/reorder', [AdminPropertyController::class, 'reorderPhotos'])->name('properties.photos.reorder');
    Route::patch('/properties/{property}/photos/{photoId}/primary', [AdminPropertyController::class, 'setPrimaryPhoto'])->name('properties.photos.primary');
    Route::delete('/properties/{property}/photos/{photoId}', [AdminPropertyController::class, 'destroyPhoto'])->name('properties.photos.destroy');

    // Clients (global, read-only — AdminClientController scopes from Client::query() directly)
    Route::resource('clients', AdminClientController::class)->only(['index', 'show']);

});