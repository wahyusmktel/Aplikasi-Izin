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
use App\Http\Controllers\WaliKelas\DashboardController as WaliKelasDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\BK\DashboardController as BKDashboardController;
use App\Http\Controllers\BK\MonitoringController as BKMonitoringController;
use App\Http\Controllers\Piket\DashboardController as PiketDashboardController;
use App\Http\Controllers\Piket\MonitoringController as PiketMonitoringController;
use App\Http\Controllers\Kurikulum\MataPelajaranController;
use App\Http\Controllers\Kurikulum\MasterGuruController;
use App\Http\Controllers\Kurikulum\JadwalPelajaranController;
use App\Http\Controllers\Kurikulum\DashboardController as KurikulumDashboardController;

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
        Route::get('/dashboard', [WaliKelasDashboardController::class, 'index'])->name('dashboard.index');
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

    // Grup Route untuk Siswa
    Route::middleware(['role:Siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard.index');
    });

    // Grup Route untuk Guru BK
    Route::middleware(['role:Guru BK'])->prefix('bk')->name('bk.')->group(function () {
        Route::get('/dashboard', [BKDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/monitoring-izin', [BKMonitoringController::class, 'index'])->name('monitoring.index');
    });

    // Grup Route untuk Guru Piket
    Route::middleware(['role:Guru Piket'])->prefix('piket')->name('piket.')->group(function () {
        Route::get('/dashboard', [PiketDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/monitoring-izin', [PiketMonitoringController::class, 'index'])->name('monitoring.index');
    });

    // Grup Route untuk Kurikulum
    Route::middleware(['role:Kurikulum'])->prefix('kurikulum')->name('kurikulum.')->group(function () {
        Route::get('/dashboard', [KurikulumDashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::post('master-guru/{master_guru}/generate-akun', [MasterGuruController::class, 'generateAkun'])->name('master-guru.generate-akun');
        Route::resource('master-guru', MasterGuruController::class);
        Route::get('jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal-pelajaran.index');
        Route::get('jadwal-pelajaran/{rombel}', [JadwalPelajaranController::class, 'show'])->name('jadwal-pelajaran.show');
        Route::post('jadwal-pelajaran/{rombel}', [JadwalPelajaranController::class, 'store'])->name('jadwal-pelajaran.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
