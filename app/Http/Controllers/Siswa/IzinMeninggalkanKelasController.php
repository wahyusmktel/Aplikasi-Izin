<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IzinMeninggalkanKelasController extends Controller
{
    public function index()
    {
        $riwayatIzin = IzinMeninggalkanKelas::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.siswa.izin-keluar-kelas.index', compact('riwayatIzin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'estimasi_kembali' => 'required|date_format:H:i',
        ]);

        try {
            $user = Auth::user();
            $rombelAktif = $user->masterSiswa->rombels()->where('tahun_ajaran', '2024/2025')->first();

            if (!$rombelAktif) {
                toast('Anda tidak terdaftar di rombel manapun pada tahun ajaran ini.', 'error');
                return back();
            }

            IzinMeninggalkanKelas::create([
                'user_id' => $user->id,
                'rombel_id' => $rombelAktif->id,
                'tujuan' => $request->tujuan,
                'keterangan' => $request->keterangan,
                'estimasi_kembali' => now()->setTimeFromTimeString($request->estimasi_kembali),
                'status' => 'diajukan',
            ]);

            toast('Pengajuan berhasil dikirim. Silakan temui guru kelas Anda.', 'success');
            return redirect()->route('siswa.izin-keluar-kelas.index');
        } catch (\Exception $e) {
            Log::error('Error creating leave permit: ' . $e->getMessage());
            toast('Gagal membuat pengajuan izin.', 'error');
            return back()->withInput();
        }
    }
}
