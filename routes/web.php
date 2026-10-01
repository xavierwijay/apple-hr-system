<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route yang membutuhkan Auth
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Employees list (Bisa diakses Admin & User)
    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');

    // Route Profile Bawaan Breeze (Mencegah error Route [profile.edit] not defined)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Khusus Admin (Dibatasi oleh Middleware 'admin')
    Route::middleware(['admin'])->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

});

require __DIR__.'/auth.php';