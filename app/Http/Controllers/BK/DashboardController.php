<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Izin Harian (untuk hari ini)
        $izinHariIni = Perizinan::with(['user.masterSiswa.rombels.kelas'])
            ->whereDate('tanggal_izin', today())
            ->latest()
            ->get();

        // 2. Data untuk Chart Siswa Paling Sering Izin
        $topSiswaIzin = User::role('Siswa')
            ->withCount('perizinan')
            ->orderBy('perizinan_count', 'desc')
            ->take(5) // Ambil 5 siswa teratas
            ->get();
            
        $topSiswaChartData = [
            'labels' => $topSiswaIzin->pluck('name'),
            'data' => $topSiswaIzin->pluck('perizinan_count'),
        ];

        return view('pages.bk.dashboard.index', compact(
            'izinHariIni',
            'topSiswaChartData'
        ));
    }
}
