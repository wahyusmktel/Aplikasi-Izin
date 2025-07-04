<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\MasterSiswa;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $waliKelas = Auth::user();

        // Ambil ID user dari siswa-siswa di bawah perwalian wali kelas ini
        $userIds = MasterSiswa::whereHas('rombels', function ($query) use ($waliKelas) {
            $query->where('wali_kelas_id', $waliKelas->id);
        })->whereNotNull('user_id')->pluck('user_id');

        // 1. Data untuk Pie Chart Status Izin
        $statusData = Perizinan::whereIn('user_id', $userIds)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChartData = [
            'labels' => $statusData->keys(),
            'data' => $statusData->values(),
        ];

        // 2. Data untuk Line Chart Tren Harian (15 hari terakhir)
        $dailyData = Perizinan::whereIn('user_id', $userIds)
            ->where('tanggal_izin', '>=', now()->subDays(15))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(tanggal_izin) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->pluck('count', 'date');

        $dates = collect();
        for ($i = 14; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->put($date, $dailyData->get($date, 0));
        }
        
        $dailyChartData = [
            'labels' => $dates->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')),
            'data' => $dates->values(),
        ];

        // 3. Widget Aktivitas Terakhir
        $latestActivities = Perizinan::whereIn('user_id', $userIds)
            ->with(['user'])
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        return view('pages.wali-kelas.dashboard.index', compact(
            'statusChartData', 
            'dailyChartData',
            'latestActivities'
        ));
    }
}
