<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\MasterGuru;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data untuk Widget Ringkasan
        $totalGuru = MasterGuru::count();
        $totalMapel = MataPelajaran::count();
        $totalRombel = Rombel::where('tahun_ajaran', '2024/2025')->count(); // Ganti dengan tahun ajaran dinamis

        // 2. Data untuk Widget Jadwal Hari Ini
        $namaHariIni = $this->getNamaHari(now()->dayOfWeek);
        $jadwalQuery = JadwalPelajaran::with(['rombel.kelas', 'mataPelajaran', 'guru'])
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan jadwal berdasarkan nama kelas untuk tampilan yang lebih baik
        $jadwalHariIni = $jadwalQuery->groupBy('rombel.kelas.nama_kelas');

        // 3. Data untuk Chart Mata Pelajaran Paling Banyak Jamnya
        $mapelChart = JadwalPelajaran::with('mataPelajaran')
            ->select('mata_pelajaran_id', DB::raw('count(*) as total_jam'))
            ->groupBy('mata_pelajaran_id')
            ->orderBy('total_jam', 'desc')
            ->take(7)
            ->get();

        $mapelChartData = [
            'labels' => $mapelChart->map(fn($item) => $item->mataPelajaran->nama_mapel),
            'data' => $mapelChart->pluck('total_jam'),
        ];

        return view('pages.kurikulum.dashboard.index', compact(
            'totalGuru',
            'totalMapel',
            'totalRombel',
            'jadwalHariIni',
            'mapelChartData'
        ));
    }

    private function getNamaHari($dayOfWeek)
    {
        $hari = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        return $hari[$dayOfWeek] ?? 'Tidak Diketahui';
    }
}
