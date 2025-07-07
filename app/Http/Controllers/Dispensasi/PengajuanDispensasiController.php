<?php

namespace App\Http\Controllers\Dispensasi;

use App\Http\Controllers\Controller;
use App\Models\Dispensasi;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengajuanDispensasiController extends Controller
{
    // Menampilkan riwayat pengajuan oleh user yang login
    public function index()
    {
        $riwayatDispensasi = Dispensasi::withCount('siswa')
            ->where('diajukan_oleh_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('pages.dispensasi.pengajuan.index', compact('riwayatDispensasi'));
    }

    // Menampilkan form untuk membuat pengajuan baru
    public function create()
    {
        $siswa = MasterSiswa::with('rombels.kelas')->orderBy('nama_lengkap')->get();
        return view('pages.dispensasi.pengajuan.create', compact('siswa'));
    }

    // Menyimpan pengajuan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:master_siswa,id',
        ]);

        DB::beginTransaction();
        try {
            $dispensasi = Dispensasi::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'keterangan' => $request->keterangan,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'diajukan_oleh_id' => Auth::id(),
                'status' => 'diajukan',
            ]);

            // Melampirkan semua siswa yang dipilih ke dalam dispensasi
            $dispensasi->siswa()->attach($request->siswa_ids);

            DB::commit();
            toast('Pengajuan dispensasi berhasil dikirim.', 'success');
            return redirect()->route('dispensasi.pengajuan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating dispensation: ' . $e->getMessage());
            toast('Gagal membuat pengajuan dispensasi.', 'error');
            return back()->withInput();
        }
    }
}
