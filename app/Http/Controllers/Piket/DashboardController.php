<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Izin Harian (untuk widget)
        $izinHariIni = Perizinan::with(['user.masterSiswa.rombels.kelas'])
            ->whereDate('tanggal_izin', today())
            ->latest('updated_at')
            ->get();

        // 2. Data untuk Pie Chart Status Izin
        $statusData = Perizinan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $statusChartData = [
            'labels' => $statusData->keys(),
            'data' => $statusData->values(),
        ];

        // 3. Data untuk Line Chart Tren Harian (30 hari terakhir)
        $dailyData = Perizinan::where('tanggal_izin', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(tanggal_izin) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->put($date, $dailyData->get($date, 0));
        }
        $dailyChartData = [
            'labels' => $dates->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')),
            'data' => $dates->values(),
        ];

        // 4. Data untuk Bar Chart Izin per Rombel
        $tahunAjaranAktif = '2024/2025'; // Nanti ini bisa dibuat dinamis
        $rombelData = Rombel::where('tahun_ajaran', $tahunAjaranAktif)
            ->with('kelas')
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->select('rombels.id', 'kelas.nama_kelas', DB::raw('(SELECT COUNT(*) FROM perizinan JOIN users ON perizinan.user_id = users.id JOIN master_siswa ON users.id = master_siswa.user_id JOIN rombel_siswa ON master_siswa.id = rombel_siswa.master_siswa_id WHERE rombel_siswa.rombel_id = rombels.id) as total_izin'))
            ->orderBy('total_izin', 'desc')
            ->get();
        $rombelChartData = [
            'labels' => $rombelData->pluck('nama_kelas'),
            'data' => $rombelData->pluck('total_izin'),
        ];
        
        return view('pages.piket.dashboard.index', compact(
            'izinHariIni', 
            'statusChartData', 
            'dailyChartData', 
            'rombelChartData'
        ));
    }
}
