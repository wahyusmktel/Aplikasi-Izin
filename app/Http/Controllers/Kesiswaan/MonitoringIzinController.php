<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MonitoringIzinController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Perizinan::with(['user.masterSiswa.rombels.kelas', 'approver']);

            // Filter berdasarkan rentang tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_izin', [$request->start_date, $request->end_date]);
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan kelas
            if ($request->filled('kelas_id')) {
                $kelasId = $request->kelas_id;
                $query->whereHas('user.masterSiswa.rombels', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            // Filter berdasarkan pencarian nama siswa
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            $perizinan = $query->latest()->paginate(15)->withQueryString();
            $kelas = Kelas::orderBy('nama_kelas')->get();

            return view('pages.kesiswaan.monitoring-izin.index', compact('perizinan', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Error fetching all permissions for Kesiswaan: ' . $e->getMessage());
            toast('Gagal memuat data monitoring perizinan.', 'error');
            return redirect()->back();
        }
    }
}
