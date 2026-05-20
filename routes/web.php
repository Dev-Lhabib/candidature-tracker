<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\EntretienController;

// Redirect root and dashboard to candidatures.index
Route::redirect('/', '/candidatures');
Route::redirect('/dashboard', '/candidatures')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidature CRUD
    Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
    Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{candidature}/edit', [CandidatureController::class, 'edit'])->name('candidatures.edit');
    Route::patch('/candidatures/{candidature}', [CandidatureController::class, 'update'])->name('candidatures.update');
    Route::delete('/candidatures/{candidature}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');

    // Archives (soft deletes)
    Route::get('/archives', [CandidatureController::class, 'archives'])->name('candidatures.archives');
    Route::patch('/candidatures/{id}/restore', [CandidatureController::class, 'restore'])->name('candidatures.restore');
    Route::delete('/candidatures/{id}/force', [CandidatureController::class, 'forceDelete'])->name('candidatures.forceDelete');

    // Entretiens (interviews) nested CRUD
    Route::get('/candidatures/{candidature}/entretiens/create', [EntretienController::class, 'create'])->name('entretiens.create');
    Route::post('/candidatures/{candidature}/entretiens', [EntretienController::class, 'store'])->name('entretiens.store');
    Route::get('/entretiens/{entretien}/edit', [EntretienController::class, 'edit'])->name('entretiens.edit');
    Route::patch('/entretiens/{entretien}', [EntretienController::class, 'update'])->name('entretiens.update');
    Route::delete('/entretiens/{entretien}', [EntretienController::class, 'destroy'])->name('entretiens.destroy');
});

require __DIR__.'/auth.php';
