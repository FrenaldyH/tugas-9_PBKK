<?php

use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// The app has no landing page; guests are sent on to the login by the auth middleware
Route::redirect('/', '/chat-sessions');

// Breeze sends users to route('dashboard') after login and register; the chat list is the real home page
Route::redirect('/dashboard', '/chat-sessions')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // chat-sessions.index and chat-sessions.show
    Route::resource('chat-sessions', ChatSessionController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
