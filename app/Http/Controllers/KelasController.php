<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Kelas::query();
            if ($request->filled('search')) {
                $query->where('nama_kelas', 'like', '%' . $request->search . '%')
                      ->orWhere('jurusan', 'like', '%' . $request->search . '%');
            }
            $kelas = $query->latest()->paginate(10);
            return view('pages.master-data.kelas.index', compact('kelas'));
        } catch (\Exception $e) {
            Log::error('Error fetching classes: ' . $e->getMessage());
            toast('Gagal memuat data kelas.', 'error');
            return back();
        }
    }

    public function create()
    {
        return view('pages.master-data.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'jurusan' => 'required|string|max:255',
        ]);

        try {
            Kelas::create($request->all());
            toast('Data kelas berhasil ditambahkan.', 'success');
            return redirect()->route('master-data.kelas.index');
        } catch (\Exception $e) {
            Log::error('Error storing class: ' . $e->getMessage());
            toast('Gagal menambahkan data kelas.', 'error');
            return back()->withInput();
        }
    }

    public function edit(Kelas $kela) // Laravel akan resolve ke $kelas
    {
        return view('pages.master-data.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id,
            'jurusan' => 'required|string|max:255',
        ]);

        try {
            $kela->update($request->all());
            toast('Data kelas berhasil diperbarui.', 'success');
            return redirect()->route('master-data.kelas.index');
        } catch (\Exception $e) {
            Log::error('Error updating class: ' . $e->getMessage());
            toast('Gagal memperbarui data kelas.', 'error');
            return back()->withInput();
        }
    }

    public function destroy(Kelas $kela)
    {
        // Pengecekan penting: jangan hapus kelas jika masih digunakan di rombel
        if ($kela->rombels()->exists()) {
            toast('Gagal menghapus! Kelas masih digunakan di Rombongan Belajar.', 'error');
            return back();
        }

        try {
            $kela->delete();
            toast('Data kelas berhasil dihapus.', 'success');
            return redirect()->route('master-data.kelas.index');
        } catch (\Exception $e) {
            Log::error('Error deleting class: ' . $e->getMessage());
            toast('Gagal menghapus data kelas.', 'error');
            return back();
        }
    }
}