<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventSessionController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController; 
use App\Http\Controllers\UserController;

// --- RUTE PUBLIK ---
Route::get('/', [EventController::class, 'guestIndex'])->name('home');
Route::get('/events', [EventController::class, 'guestIndex'])->name('events.guest.index');
Route::get('/events/{event}', [EventController::class, 'showPublic'])->name('events.show.public');

// --- RUTE UNTUK PENGGUNA YANG SUDAH LOGIN ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/sessions/{session}/register', [EventSessionController::class, 'register'])
        ->middleware('role:member') // Tetap dilindungi untuk member
        ->name('sessions.register'); // Namanya sekarang benar

    // --- Grup Rute Khusus Member ---
    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/my-registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/{registration}/payment', [RegistrationController::class, 'payment'])->name('registrations.payment');
        Route::post('/registrations/{registration}/payment', [RegistrationController::class, 'processPayment'])->name('registrations.processPayment');
    });

    // --- Grup Rute Khusus Panitia Kegiatan ---
    Route::middleware('role:panitia_kegiatan')->prefix('committee')->name('committee.')->group(function () {
        Route::resource('events', EventController::class); 
        Route::resource('events.sessions', EventSessionController::class)->except(['index', 'show'])->shallow();
        
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/{session}/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('/attendance/process', [AttendanceController::class, 'processScan'])->name('attendance.process');

        // RUTE UNTUK SERTIFIKAT
        Route::get('/certificates/{session}', [CertificateController::class, 'index'])->name('certificates.index');
        Route::post('/certificates/{registration}/upload', [CertificateController::class, 'upload'])->name('certificates.upload');
    });

    // --- Grup Rute Khusus Tim Keuangan ---
    Route::middleware('role:tim_keuangan')->prefix('finance')->name('finance.')->group(function () {
        Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
        Route::post('/verifications/{registration}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{registration}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');
    }); 

    // --- Grup Rute Khusus Administrator ---
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('event-categories', EventCategoryController::class);
    
            // TAMBAHKAN RUTE INI UNTUK KELOLA PENGGUNA
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    });

});

require __DIR__.'/auth.php';

