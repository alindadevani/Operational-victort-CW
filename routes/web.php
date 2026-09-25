<?php

use App\Http\Controllers\Admin\AttendanceHistoryController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BorrowingHistoryController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\KepalaCabang\DashboardController as KepalaCabangDashboardController;
use App\Http\Controllers\Marketing\BorrowingController as MarketingBorrowingController;
use App\Http\Controllers\Marketing\DashboardController as MarketingDashboardController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Umum
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| ROUTE ABSENSI (PUBLIK - TANPA LOGIN)
|--------------------------------------------------------------------------
| Dibuka lewat scan QR Code. Karyawan mengisi nama, pilih team,
| lalu sistem otomatis mencatat tanggal & jam.
*/

Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

// Jadwal mobil bisa dilihat semua user yang sudah login.
Route::middleware('auth')->get('/jadwal-mobil', [ScheduleController::class, 'index'])->name('schedule.index');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN OPERASIONAL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

        // CRUD Team
        Route::resource('teams', TeamController::class)->except(['show']);

        // CRUD Mobil
        Route::resource('cars', CarController::class)->except(['show']);

        // Generate QR Code Absensi
        Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode');

        // History Absensi (khusus admin: bisa lihat semua + export)
        Route::get('/attendance-history', [AttendanceHistoryController::class, 'index'])->name('attendance-history');
        Route::get('/attendance-history/export', [AttendanceHistoryController::class, 'export'])->name('attendance-history.export');

        // History & Detail Peminjaman Mobil
        Route::get('/borrowings', [BorrowingHistoryController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/export', [BorrowingHistoryController::class, 'export'])->name('borrowings.export');
        Route::put('/borrowings/{borrowing}/approve', [BorrowingHistoryController::class, 'approve'])->name('borrowings.approve');
        Route::put('/borrowings/{borrowing}/reject', [BorrowingHistoryController::class, 'reject'])->name('borrowings.reject');
        Route::put('/borrowings/{borrowing}/complete', [BorrowingHistoryController::class, 'complete'])->name('borrowings.complete');
        Route::get('/borrowings/{borrowing}', [BorrowingHistoryController::class, 'show'])->name('borrowings.show');
    });

/*
|--------------------------------------------------------------------------
| ROUTE KEPALA CABANG (READ-ONLY / MONITORING)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kepala_cabang'])
    ->prefix('kepala-cabang')
    ->name('kepala-cabang.')
    ->group(function () {
        Route::get('/dashboard', [KepalaCabangDashboardController::class, 'index'])->name('dashboard');

        // Kepala cabang memakai controller history yang sama dengan admin,
        // tapi lewat route berbeda supaya middleware role tetap terpisah.
        // View akan otomatis menyembunyikan tombol edit/hapus untuk role ini.
        Route::get('/attendance-history', [AttendanceHistoryController::class, 'index'])->name('attendance-history');
        Route::get('/borrowings', [BorrowingHistoryController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/{borrowing}', [BorrowingHistoryController::class, 'show'])->name('borrowings.show');
    });

/*
|--------------------------------------------------------------------------
| ROUTE MARKETING / MT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:marketing'])
    ->prefix('marketing')
    ->name('marketing.')
    ->group(function () {
        Route::get('/dashboard', [MarketingDashboardController::class, 'index'])->name('dashboard');

        // Form Peminjaman Mobil
        Route::get('/borrowings/create', [MarketingBorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [MarketingBorrowingController::class, 'store'])->name('borrowings.store');

        // Riwayat peminjaman milik sendiri
        Route::get('/borrowings/history', [MarketingBorrowingController::class, 'history'])->name('borrowings.history');
        Route::get('/borrowings/{borrowing}/signature', [MarketingBorrowingController::class, 'showSignatureForm'])->name('borrowings.signature.create');
        Route::post('/borrowings/{borrowing}/signature', [MarketingBorrowingController::class, 'storeSignature'])->name('borrowings.signature.store');
    });

require __DIR__.'/auth.php';
