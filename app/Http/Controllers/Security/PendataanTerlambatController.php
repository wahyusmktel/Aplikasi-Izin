<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Keterlambatan;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendataanTerlambatController extends Controller
{
    public function index(Request $request)
    {
        $hasilPencarian = null;
        if ($request->filled('search')) {
            $hasilPencarian = MasterSiswa::with('rombels.kelas')
                ->where('nama_lengkap', 'like', '%' . $request->search . '%')
                ->orWhere('nis', 'like', '%' . $request->search . '%')
                ->get();
        }
        return view('pages.security.pendataan-terlambat.index', compact('hasilPencarian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_siswa_id' => 'required|exists:master_siswa,id',
            'alasan_siswa' => 'required|string|min:5',
        ]);

        try {
            Keterlambatan::create([
                'master_siswa_id' => $request->master_siswa_id,
                'alasan_siswa' => $request->alasan_siswa,
                'dicatat_oleh_security_id' => Auth::id(),
                'waktu_dicatat_security' => now(),
                'status' => 'dicatat_security',
            ]);

            toast('Data berhasil dicatat. Arahkan siswa ke Ruang Piket.', 'success');
            return redirect()->route('security.pendataan-terlambat.index');
        } catch (\Exception $e) {
            Log::error('Error storing initial late record: ' . $e->getMessage());
            toast('Gagal menyimpan data keterlambatan.', 'error');
            return back()->withInput();
        }
    }
}
