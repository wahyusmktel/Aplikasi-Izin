<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use App\Models\Perizinan;
use App\Models\Rombel;
use App\Models\User; // <-- Pastikan User model di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $piketUserId = Auth::id();

        // ==================================================
        //      BAGIAN 1: DATA LAMA ANDA (IZIN TIDAK MASUK)
        // ==================================================

        // Data untuk Widget Izin Hari Ini (Izin Tidak Masuk)
        $izinHariIni = Perizinan::with(['user.masterSiswa.rombels.kelas'])
            ->whereDate('tanggal_izin', today())
            ->latest('updated_at')
            ->get();

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
            ])->pluck('count', 'date');
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->put($date, $dailyData->get($date, 0));
        }

        // Data untuk Bar Chart Izin per Rombel
        $tahunAjaranAktif = '2024/2025';
        $rombelData = Rombel::where('tahun_ajaran', $tahunAjaranAktif)
            ->with('kelas')
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->select('rombels.id', 'kelas.nama_kelas', DB::raw('(SELECT COUNT(*) FROM perizinan JOIN users ON perizinan.user_id = users.id JOIN master_siswa ON users.id = master_siswa.user_id JOIN rombel_siswa ON master_siswa.id = rombel_siswa.master_siswa_id WHERE rombel_siswa.rombel_id = rombels.id) as total_izin'))
            ->orderBy('total_izin', 'desc')
            ->get();


        // ==================================================
        //      BAGIAN 2: DATA BARU (IZIN MENINGGALKAN KELAS)
        // ==================================================

        // Widget: Total Izin Diproses oleh Anda
        $totalIzinDiprosesPiket = IzinMeninggalkanKelas::where('guru_piket_approval_id', $piketUserId)->count();

        // Grafik: Tren Harian Izin yang Anda Proses
        $dailyDataPiket = IzinMeninggalkanKelas::where('guru_piket_approval_id', $piketUserId)
            ->where('guru_piket_approved_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(guru_piket_approved_at) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');
        $datesPiket = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $datesPiket->put($date, $dailyDataPiket->get($date, 0));
        }

        // Grafik: Top 5 Tujuan Izin yang Anda Setujui
        $tujuanChartPiket = IzinMeninggalkanKelas::where('guru_piket_approval_id', $piketUserId)
            ->select('tujuan', DB::raw('count(*) as total'))
            ->groupBy('tujuan')
            ->orderBy('total', 'desc')
            ->take(5)
            ->pluck('total', 'tujuan');

        // ==================================================
        //      PERBAIKAN QUERY TOP 10 SISWA
        // ==================================================
        // Widget Top 10 Siswa (Personal - yang Anda proses)
        $topSiswaPersonalData = IzinMeninggalkanKelas::select('user_id', DB::raw('count(*) as total_izin'))
            ->where('guru_piket_approval_id', $piketUserId)
            ->groupBy('user_id')
            ->orderBy('total_izin', 'desc')
            ->take(10)
            ->get();
        $topSiswaPersonalIds = $topSiswaPersonalData->pluck('user_id');
        $topSiswaPersonalUsers = User::whereIn('id', $topSiswaPersonalIds)->get()->keyBy('id');
        $topSiswaIzinKeluarPersonal = $topSiswaPersonalData->map(function ($item) use ($topSiswaPersonalUsers) {
            $user = $topSiswaPersonalUsers->get($item->user_id);
            if ($user) {
                $user->izin_meninggalkan_kelas_count = $item->total_izin;
                return $user;
            }
            return null;
        })->filter();

        // Widget BARU: Top 10 Siswa (Global)
        $topSiswaIzinKeluarGlobal = User::role('Siswa')
            ->withCount('izinMeninggalkanKelas')
            ->orderBy('izin_meninggalkan_kelas_count', 'desc')
            ->take(10)
            ->get();


        // ==================================================
        //      BAGIAN 3: MENGIRIM SEMUA DATA KE VIEW
        // ==================================================
        return view('pages.piket.dashboard.index', [
            // Variabel dari statistik umum
            'izinHariIni' => $izinHariIni,
            'statusChartData' => ['labels' => $statusData->keys(), 'data' => $statusData->values()],
            'dailyChartData' => ['labels' => $dates->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')), 'data' => $dates->values()],
            'rombelChartData' => ['labels' => $rombelData->pluck('nama_kelas'), 'data' => $rombelData->pluck('total_izin')],

            // Variabel untuk statistik personal & global Guru Piket
            'totalIzinDiprosesPiket' => $totalIzinDiprosesPiket,
            'dailyChartDataPiket' => ['labels' => $datesPiket->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')), 'data' => $datesPiket->values()],
            'tujuanChartDataPiket' => ['labels' => $tujuanChartPiket->keys(), 'data' => $tujuanChartPiket->values()],
            'topSiswaIzinKeluarPersonal' => $topSiswaIzinKeluarPersonal,
            'topSiswaIzinKeluarGlobal' => $topSiswaIzinKeluarGlobal, // <-- Variabel baru ditambahkan
        ]);
    }
}
