<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// ── Routes publiques ──────────────────────────────────────────────────────────

Route::get('/', fn () => redirect()->route('login'));

// Inscription via code d'invitation
Route::get('/register/{code}',  [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register/{code}', [RegisteredUserController::class, 'store']);

// Connexion / déconnexion (gérées par Breeze)
require __DIR__.'/auth.php';

// ── Routes authentifiées ──────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Documents
    Route::resource('documents', DocumentController::class)
        ->only(['index', 'show', 'create', 'store', 'destroy']);

    // Téléchargement (route séparée du show pour forcer le download)
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // Profil — mes documents
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');

    // ── Espace formateur ──────────────────────────────────────────────────────
    Route::middleware('role:formateur')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('invitations', InvitationController::class)
            ->only(['index', 'store', 'destroy']);
    });

});
