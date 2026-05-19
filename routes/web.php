<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// Temporaire — page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Inscription via code d'invitation
Route::get('/register/{code}',  [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register/{code}', [RegisteredUserController::class, 'store']);

// Auth Breeze
require __DIR__.'/auth.php';

// Route temporaire documents
Route::middleware('auth')->group(function () {
    Route::get('/documents', function () {
        return 'Connecté ! Documents à venir.';
    })->name('documents.index');
});
