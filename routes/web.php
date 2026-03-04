<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->middleware('can:manage-assessments')->name('assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->middleware('can:manage-assessments')->name('assessments.update');
    Route::get('/assessments/{assessment}/take', [AssessmentController::class, 'take'])->name('assessments.take');

    Route::get('/users', [UserController::class, 'index'])->middleware('can:admin')->name('users.index');
    Route::get('/ask-trudy', fn () => view('ask-trudy'))->name('ask-trudy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
