<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CandidatureFichierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntretienController;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->scopeBindings()->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
    Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::get('/candidatures/archives', [CandidatureController::class, 'archives'])->name('candidatures.archives');
    Route::put('/candidatures/{id}/restore', [CandidatureController::class, 'restore'])->name('candidatures.restore');
    Route::delete('/candidatures/{id}/force', [CandidatureController::class, 'forceDelete'])->name('candidatures.forceDelete');
    Route::get('/candidatures/{candidature}/fichiers/{fichier}/download', [CandidatureFichierController::class, 'download'])->name('candidatures.fichiers.download');
    Route::delete('/candidatures/{candidature}/fichiers/{fichier}', [CandidatureFichierController::class, 'destroy'])->name('candidatures.fichiers.destroy');
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{candidature}/edit', [CandidatureController::class, 'edit'])->name('candidatures.edit');
    Route::put('/candidatures/{candidature}', [CandidatureController::class, 'update'])->name('candidatures.update');
    Route::delete('/candidatures/{candidature}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');

    Route::get('/entretiens/create', [EntretienController::class, 'create'])->name('entretiens.create');
    Route::post('/entretiens', [EntretienController::class, 'store'])->name('entretiens.store');
    Route::get('/entretiens/{entretien}/edit', [EntretienController::class, 'edit'])->name('entretiens.edit');
    Route::put('/entretiens/{entretien}', [EntretienController::class, 'update'])->name('entretiens.update');
    Route::delete('/entretiens/{entretien}', [EntretienController::class, 'destroy'])->name('entretiens.destroy');
});

require __DIR__.'/auth.php';