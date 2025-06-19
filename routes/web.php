<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminRegistrationController;

Route::get('/register', [RegistrationController::class, 'index'])->name('register.form');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.submit');
Route::get('/departments/{facultyId}', [RegistrationController::class, 'getDepartments']);
Route::put('/register/{id}', [RegistrationController::class, 'update'])->name('register.update');


// Admin backend route for registration check
Route::get('/admin/registrations', [AdminRegistrationController::class, 'index'])->name('admin.registrations');

// Admin backend registration CRUD routes
Route::post('/admin/registrations', [AdminRegistrationController::class, 'store'])->name('admin.registrations.store');
Route::get('/admin/registrations/{id}', [AdminRegistrationController::class, 'show'])->name('admin.registrations.show');
Route::put('/admin/registrations/{id}', [AdminRegistrationController::class, 'update'])->name('admin.registrations.update');
Route::delete('/admin/registrations/{id}', [AdminRegistrationController::class, 'destroy'])->name('admin.registrations.destroy');

// Exam Slot routes
Route::post('/admin/exam-slots', [AdminRegistrationController::class, 'storeExamSlot'])->name('admin.exam-slots.store');
Route::put('/admin/exam-slots/{id}', [AdminRegistrationController::class, 'updateExamSlot'])->name('admin.exam-slots.update');
Route::delete('/admin/exam-slots/{id}', [AdminRegistrationController::class, 'destroyExamSlot'])->name('admin.exam-slots.destroy');
