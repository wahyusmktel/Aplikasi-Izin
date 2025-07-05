<?php

namespace App\Http\Controllers\GuruKelas;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\MasterGuru;
use App\Models\Perizinan;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterGuru = MasterGuru::where('user_id', $user->id)->first();

        // Jika data master guru tidak ditemukan, kembalikan view kosong
        if (!$masterGuru) {
            return view('pages.guru-kelas.dashboard.index', [
                'kelasDiajar' => collect(),
                'jadwalHariIni' => collect(),
                'siswaIzinHariIni' => collect()
            ]);
        }

        // 1. Data untuk Widget Kelas & Siswa yang Diajar
        $rombelIds = JadwalPelajaran::where('master_guru_id', $masterGuru->id)
            ->distinct()
            ->pluck('rombel_id');

        $kelasDiajar = Rombel::withCount('siswa')
            ->with('kelas')
            ->whereIn('id', $rombelIds)
            ->get();

        // 2. Data untuk Widget Jadwal Mengajar Hari Ini
        $namaHariIni = $this->getNamaHari(now()->dayOfWeek);
        $jadwalHariIni = JadwalPelajaran::with(['rombel.kelas', 'mataPelajaran'])
            ->where('master_guru_id', $masterGuru->id)
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai')
            ->get();

        // 3. Data untuk Widget Siswa Izin Hari Ini (dari kelas yang diajar)
        $siswaIzinHariIni = Perizinan::with(['user', 'user.masterSiswa.rombels.kelas'])
            ->where('status', '!=', 'ditolak') // Tampilkan yang diajukan & disetujui
            ->whereDate('tanggal_izin', today())
            ->whereHas('user.masterSiswa.rombels', function ($query) use ($rombelIds) {
                $query->whereIn('rombels.id', $rombelIds);
            })
            ->get();

        return view('pages.guru-kelas.dashboard.index', compact(
            'kelasDiajar',
            'jadwalHariIni',
            'siswaIzinHariIni'
        ));
    }

    private function getNamaHari($dayOfWeek)
    {
        $hari = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        return $hari[$dayOfWeek] ?? 'Tidak Diketahui';
    }
}
