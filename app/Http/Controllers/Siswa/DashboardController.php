<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Data untuk Widget Ringkasan
        $totalDiajukan = Perizinan::where('user_id', $userId)->count();
        $totalDisetujui = Perizinan::where('user_id', $userId)->where('status', 'disetujui')->count();
        $totalDitolak = Perizinan::where('user_id', $userId)->where('status', 'ditolak')->count();

        // 2. Data untuk Pie Chart
        $statusData = Perizinan::where('user_id', $userId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChartData = [
            'labels' => $statusData->keys(),
            'data' => $statusData->values(),
        ];

        return view('pages.siswa.dashboard.index', compact(
            'totalDiajukan',
            'totalDisetujui',
            'totalDitolak',
            'statusChartData'
        ));
    }
}
