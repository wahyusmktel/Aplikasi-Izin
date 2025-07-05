<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IzinMeninggalkanKelas;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================================================
        //      BAGIAN 1: DATA UNTUK IZIN TIDAK MASUK
        //      (Kode ini dari Anda dan sudah benar)
        // ==================================================

        // Data untuk Pie Chart Status Izin
        $statusData = Perizinan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Data untuk Line Chart Tren Harian (30 hari terakhir)
        $dailyData = Perizinan::where('tanggal_izin', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(tanggal_izin) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->pluck('count', 'date');

        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->put($date, $dailyData->get($date, 0));
        }

        // Data untuk Bar Chart Izin Tidak Masuk per Rombel
        $tahunAjaranAktif = '2024/2025'; // Nanti ini bisa dibuat dinamis
        $rombelIzinTidakMasukChart = Rombel::where('tahun_ajaran', $tahunAjaranAktif)
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->select('kelas.nama_kelas', DB::raw('(SELECT COUNT(*) FROM perizinan JOIN users ON perizinan.user_id = users.id JOIN master_siswa ON users.id = master_siswa.user_id JOIN rombel_siswa ON master_siswa.id = rombel_siswa.master_siswa_id WHERE rombel_siswa.rombel_id = rombels.id) as total_izin'))
            ->orderBy('total_izin', 'desc')
            ->get();

        // Data untuk Widget Aktivitas Terakhir
        $latestActivities = Perizinan::with(['user', 'approver'])
            ->latest('updated_at')
            ->take(10)
            ->get();


        // ==================================================
        //      BAGIAN 2: DATA UNTUK IZIN MENINGGALKAN KELAS
        //      (Ini adalah data baru yang kita tambahkan)
        // ==================================================

        // Widget: Top 10 Siswa Paling Sering Izin Keluar
        $topSiswaIzinKeluar = User::role('Siswa')
            ->withCount('izinMeninggalkanKelas')
            ->orderBy('izin_meninggalkan_kelas_count', 'desc')
            ->take(10)
            ->get();

        // Grafik: Rombel dengan izin keluar terbanyak
        $rombelIzinKeluarChart = Rombel::where('tahun_ajaran', $tahunAjaranAktif)
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->select('kelas.nama_kelas', DB::raw('(SELECT COUNT(*) FROM izin_meninggalkan_kelas WHERE izin_meninggalkan_kelas.rombel_id = rombels.id) as total_izin'))
            ->orderBy('total_izin', 'desc')->get();

        // Grafik: Tujuan izin keluar terbanyak
        $tujuanIzinKeluarChart = IzinMeninggalkanKelas::select('tujuan', DB::raw('count(*) as total'))
            ->groupBy('tujuan')
            ->orderBy('total', 'desc')
            ->take(5)
            ->pluck('total', 'tujuan');


        // ==================================================
        //      BAGIAN 3: MENGIRIM SEMUA DATA KE VIEW
        //      (Gabungan dari data lama dan data baru)
        // ==================================================
        return view('pages.kesiswaan.dashboard.index', [
            // Variabel dari kode lama Anda
            'statusChartData' => ['labels' => $statusData->keys(), 'data' => $statusData->values()],
            'dailyChartData' => ['labels' => $dates->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')), 'data' => $dates->values()],
            'rombelChartData' => ['labels' => $rombelIzinTidakMasukChart->pluck('nama_kelas'), 'data' => $rombelIzinTidakMasukChart->pluck('total_izin')],
            'latestActivities' => $latestActivities,

            // Variabel baru untuk statistik Izin Meninggalkan Kelas
            'topSiswaIzinKeluar' => $topSiswaIzinKeluar,
            'rombelIzinKeluarChartData' => ['labels' => $rombelIzinKeluarChart->pluck('nama_kelas'), 'data' => $rombelIzinKeluarChart->pluck('total_izin')],
            'tujuanIzinKeluarChartData' => ['labels' => $tujuanIzinKeluarChart->keys(), 'data' => $tujuanIzinKeluarChart->values()],
        ]);
    }
}
