<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('chat/stream', [ChatController::class, 'stream'])->name('chat.stream');
});

require __DIR__.'/settings.php';
