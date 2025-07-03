<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MasterSiswa;
use App\Models\Rombel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        $query = Rombel::with(['kelas', 'waliKelas']); // Eager load relasi

        if ($request->filled('search')) {
            $query->where('tahun_ajaran', 'like', '%' . $request->search . '%');
        }

        $rombel = $query->latest()->paginate(10);
        return view('pages.master-data.rombel.index', compact('rombel'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas', 'id');
        $wali_kelas = User::role('Wali Kelas')->orderBy('name')->pluck('name', 'id');
        
        return view('pages.master-data.rombel.create', compact('kelas', 'wali_kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9', // Contoh: 2024/2025
            'kelas_id' => 'required|exists:kelas,id',
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        try {
            Rombel::create($request->all());
            toast('Data rombel berhasil ditambahkan.', 'success');
            return redirect()->route('master-data.rombel.index');
        } catch (\Exception $e) {
            Log::error('Error storing rombel: ' . $e->getMessage());
            toast('Gagal menambahkan data rombel.', 'error');
            return back()->withInput();
        }
    }

    public function edit(Rombel $rombel)
    {
        $kelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas', 'id');
        $wali_kelas = User::role('Wali Kelas')->orderBy('name')->pluck('name', 'id');

        return view('pages.master-data.rombel.edit', compact('rombel', 'kelas', 'wali_kelas'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        try {
            $rombel->update($request->all());
            toast('Data rombel berhasil diperbarui.', 'success');
            return redirect()->route('master-data.rombel.index');
        } catch (\Exception $e) {
            Log::error('Error updating rombel: ' . $e->getMessage());
            toast('Gagal memperbarui data rombel.', 'error');
            return back()->withInput();
        }
    }

    public function destroy(Rombel $rombel)
    {
        // Pengecekan penting: jangan hapus rombel jika masih ada siswa di dalamnya
        if ($rombel->siswa()->exists()) {
            toast('Gagal menghapus! Masih ada siswa terdaftar di rombel ini.', 'error');
            return back();
        }
        
        try {
            $rombel->delete();
            toast('Data rombel berhasil dihapus.', 'success');
            return redirect()->route('master-data.rombel.index');
        } catch (\Exception $e) {
            Log::error('Error deleting rombel: ' . $e->getMessage());
            toast('Gagal menghapus data rombel.', 'error');
            return back();
        }
    }

    /**
     * Menampilkan halaman detail rombel untuk memetakan siswa.
     */
    public function show(Rombel $rombel)
    {
        // 1. Ambil siswa yang sudah ada di rombel ini
        $siswaDiRombel = $rombel->siswa()->orderBy('nama_lengkap')->get();
        $siswaDiRombelIds = $siswaDiRombel->pluck('id')->toArray();

        // 2. Ambil siswa yang tersedia (yang belum masuk rombel manapun di tahun ajaran yang sama)
        $siswaTersedia = MasterSiswa::whereNotIn('id', function($query) use ($rombel) {
            $query->select('master_siswa_id')
                  ->from('rombel_siswa')
                  ->join('rombels', 'rombels.id', '=', 'rombel_siswa.rombel_id')
                  ->where('rombels.tahun_ajaran', $rombel->tahun_ajaran);
        })->orderBy('nama_lengkap')->get();

        return view('pages.master-data.rombel.show', compact('rombel', 'siswaDiRombel', 'siswaTersedia'));
    }

    /**
     * Menambahkan satu atau lebih siswa ke dalam rombel.
     */
    public function addSiswa(Request $request, Rombel $rombel)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:master_siswa,id',
        ]);

        try {
            // attach() akan menambahkan relasi baru tanpa duplikasi
            $rombel->siswa()->attach($request->siswa_ids);
            toast('Siswa berhasil ditambahkan ke rombel.', 'success');
            return back();
        } catch (\Exception $e) {
            Log::error('Error adding students to rombel: ' . $e->getMessage());
            toast('Gagal menambahkan siswa.', 'error');
            return back();
        }
    }

    /**
     * Mengeluarkan seorang siswa dari rombel.
     */
    public function removeSiswa(Rombel $rombel, MasterSiswa $siswa)
    {
        try {
            // detach() akan menghapus relasi
            $rombel->siswa()->detach($siswa->id);
            toast('Siswa berhasil dikeluarkan dari rombel.', 'success');
            return back();
        } catch (\Exception $e) {
            Log::error('Error removing student from rombel: ' . $e->getMessage());
            toast('Gagal mengeluarkan siswa.', 'error');
            return back();
        }
    }
}