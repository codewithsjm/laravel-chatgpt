<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('chat/stream', [ChatController::class, 'stream'])
        ->middleware('throttle:chat')
        ->name('chat.stream');
    Route::get('chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::patch('chat/{conversation}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('chat/{conversation}', [ChatController::class, 'destroy'])->name('chat.destroy');
});

require __DIR__.'/settings.php';
