<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\WaliKelas\PerizinanController as WaliKelasPerizinanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MasterData\MasterSiswaController;
use App\Http\Controllers\MasterData\RombelController;
use App\Http\Controllers\Kesiswaan\MonitoringIzinController;
use App\Http\Controllers\Kesiswaan\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // ... route dashboard dan profile dari Breeze

    // Grup untuk route yang memerlukan peran Waka Kesiswaan
    Route::middleware(['role:Waka Kesiswaan'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Route untuk Perizinan
    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::put('/izin/{perizinan}', [IzinController::class, 'update'])->name('izin.update');
    Route::delete('/izin/{perizinan}', [IzinController::class, 'destroy'])->name('izin.destroy');

    // Grup Route untuk Wali Kelas
    Route::middleware(['role:Wali Kelas'])->prefix('wali-kelas')->name('wali-kelas.')->group(function () {
        Route::get('/perizinan', [WaliKelasPerizinanController::class, 'index'])->name('perizinan.index');
        Route::patch('/perizinan/{perizinan}/approve', [WaliKelasPerizinanController::class, 'approve'])->name('perizinan.approve');
        Route::patch('/perizinan/{perizinan}/reject', [WaliKelasPerizinanController::class, 'reject'])->name('perizinan.reject');
        // Nanti kita tambahkan route untuk approve & reject di sini
    });

    // Grup untuk Data Master, bisa diakses oleh Waka Kesiswaan/Admin
    Route::middleware(['role:Waka Kesiswaan'])->prefix('master-data')->name('master-data.')->group(function () {
        Route::resource('kelas', KelasController::class);

        Route::post('siswa/generate-akun-masal', [MasterSiswaController::class, 'generateAkunMasal'])->name('siswa.generate-akun-masal'); // <-- Route Generate Masal
        Route::post('siswa/{master_siswa}/generate-akun', [MasterSiswaController::class, 'generateAkun'])->name('siswa.generate-akun');
        Route::post('siswa/{master_siswa}/reset-password', [MasterSiswaController::class, 'resetPassword'])->name('siswa.reset-password'); // <-- Route Reset Password
        Route::resource('siswa', MasterSiswaController::class);

        Route::post('rombel/{rombel}/add-siswa', [RombelController::class, 'addSiswa'])->name('rombel.add-siswa');
        Route::delete('rombel/{rombel}/remove-siswa/{siswa}', [RombelController::class, 'removeSiswa'])->name('rombel.remove-siswa');
        Route::resource('rombel', RombelController::class);
    });

    // Grup Route untuk Kesiswaan
    Route::middleware(['role:Waka Kesiswaan'])->prefix('kesiswaan')->name('kesiswaan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/monitoring-izin', [MonitoringIzinController::class, 'index'])->name('monitoring-izin.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
