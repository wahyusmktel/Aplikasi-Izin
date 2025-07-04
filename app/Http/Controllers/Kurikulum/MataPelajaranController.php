<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::query();
        if ($request->filled('search')) {
            $query->where('nama_mapel', 'like', '%' . $request->search . '%')
                ->orWhere('kode_mapel', 'like', '%' . $request->search . '%');
        }
        $mapel = $query->latest()->paginate(10);
        return view('pages.kurikulum.mata-pelajaran.index', compact('mapel'));
    }

    public function create()
    {
        return view('pages.kurikulum.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:255',
            'jumlah_jam' => 'required|integer|min:0',
        ]);

        try {
            MataPelajaran::create($request->all());
            toast('Mata pelajaran berhasil ditambahkan.', 'success');
            return redirect()->route('kurikulum.mata-pelajaran.index');
        } catch (\Exception $e) {
            Log::error('Error storing subject: ' . $e->getMessage());
            toast('Gagal menambahkan mata pelajaran.', 'error');
            return back()->withInput();
        }
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('pages.kurikulum.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel,' . $mataPelajaran->id,
            'nama_mapel' => 'required|string|max:255',
            'jumlah_jam' => 'required|integer|min:0',
        ]);

        try {
            $mataPelajaran->update($request->all());
            toast('Mata pelajaran berhasil diperbarui.', 'success');
            return redirect()->route('kurikulum.mata-pelajaran.index');
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            toast('Gagal memperbarui mata pelajaran.', 'error');
            return back()->withInput();
        }
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        try {
            $mataPelajaran->delete();
            toast('Mata pelajaran berhasil dihapus.', 'success');
            return redirect()->route('kurikulum.mata-pelajaran.index');
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            toast('Gagal menghapus mata pelajaran.', 'error');
            return back();
        }
    }
}
