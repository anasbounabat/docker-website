<?php

use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', Chat::class)->name('chat');
});

require __DIR__.'/auth.php';

