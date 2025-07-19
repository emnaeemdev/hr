<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\EmployeeAdvanceController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\CustomFieldController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource routes
    Route::resource('branches', BranchController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('tools', ToolController::class);
    Route::resource('employee-advances', EmployeeAdvanceController::class);
    Route::resource('employee-documents', EmployeeDocumentController::class);
    Route::resource('custom-fields', CustomFieldController::class);
    
    // Employee specific routes
    Route::get('/employees/{employee}/advances', [EmployeeController::class, 'advances'])->name('employees.advances');
    Route::get('/employees/{employee}/documents', [EmployeeController::class, 'documents'])->name('employees.documents');
    Route::get('/employees/{employee}/tools', [EmployeeController::class, 'tools'])->name('employees.tools');
    
    // Tool assignment routes
    Route::post('/employees/{employee}/assign-tool', [EmployeeController::class, 'assignTool'])->name('employees.assign-tool');
    Route::post('/employees/{employee}/return-tool', [EmployeeController::class, 'returnTool'])->name('employees.return-tool');
    Route::put('/tools/{tool}/assign', [ToolController::class, 'assign'])->name('tools.assign');
    Route::put('/tools/{tool}/return', [ToolController::class, 'returnTool'])->name('tools.return');
    
    // Advance payment routes
    Route::post('/employee-advances/{advance}/payment', [EmployeeAdvanceController::class, 'addPayment'])->name('employee-advances.payment');
    
    // Document verification routes
    Route::post('/employee-documents/{document}/verify', [EmployeeDocumentController::class, 'verify'])->name('employee-documents.verify');
    Route::get('/employee-documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('employee-documents.download');
    
    // AJAX routes for dynamic data
    Route::get('/api/employees/by-branch/{branch}', [EmployeeController::class, 'getByBranch'])->name('api.employees.by-branch');
    Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('api.employees.search');
    Route::get('/api/tools/available', [ToolController::class, 'getAvailable'])->name('api.tools.available');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
