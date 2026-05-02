<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Add this line with your other routes
Route::get('/login', function() {
    return redirect()->route('admin.login');
})->name('login');

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/apply', [StudentController::class, 'showForm'])->name('apply');
    Route::post('/check-duplicate', [StudentController::class, 'checkDuplicate'])->name('check-duplicate');
    Route::post('/submit', [StudentController::class, 'submitApplication'])->name('submit');
    Route::get('/review/{id}', [StudentController::class, 'reviewApplication'])->name('review');
    Route::get('/edit/{id}', [StudentController::class, 'editApplication'])->name('edit');
    Route::put('/update/{id}', [StudentController::class, 'updateApplication'])->name('update');
    Route::get('/status/{id}', [StudentController::class, 'checkStatus'])->name('status');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
        Route::get('/application/{id}', [AdminController::class, 'viewApplication'])->name('view');
        Route::post('/application/{id}/approve', [AdminController::class, 'approveApplication'])->name('approve');
        Route::post('/application/{id}/reject', [AdminController::class, 'rejectApplication'])->name('reject');
        Route::post('/application/{id}/waitlist', [AdminController::class, 'waitlistApplication'])->name('waitlist');
        Route::get('/application/{id}/edit', [AdminController::class, 'editApplication'])->name('edit');
        Route::put('/application/{id}/update', [AdminController::class, 'updateApplication'])->name('update');
        Route::delete('/application/{id}/delete', [AdminController::class, 'deleteApplication'])->name('delete');
    });
});

// Auto-logout endpoint called from the React landing page on refresh
Route::get('/auto-logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['ok' => true])
        ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->header('Access-Control-Allow-Credentials', 'true');
});

// Student track application routes
Route::get('/student/track', [StudentController::class, 'showTrackPage'])->name('student.track');
Route::get('/student/lookup', [StudentController::class, 'lookupApplication'])->name('student.lookup');