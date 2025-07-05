<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JamPelajaranController extends Controller
{
    public function index()
    {
        $jamPelajaran = JamPelajaran::orderBy('jam_ke')->get();
        return view('pages.kurikulum.jam-pelajaran.index', compact('jamPelajaran'));
    }

    public function create()
    {
        return view('pages.kurikulum.jam-pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jam_ke' => 'required|integer|unique:jam_pelajarans,jam_ke',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            JamPelajaran::create($request->all());
            toast('Jam pelajaran berhasil ditambahkan.', 'success');
            return redirect()->route('kurikulum.jam-pelajaran.index');
        } catch (\Exception $e) {
            Log::error('Error storing time slot: ' . $e->getMessage());
            toast('Gagal menambahkan jam pelajaran.', 'error');
            return back()->withInput();
        }
    }

    public function edit(JamPelajaran $jamPelajaran)
    {
        return view('pages.kurikulum.jam-pelajaran.edit', compact('jamPelajaran'));
    }

    public function update(Request $request, JamPelajaran $jamPelajaran)
    {
        $request->validate([
            'jam_ke' => 'required|integer|unique:jam_pelajarans,jam_ke,' . $jamPelajaran->id,
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $jamPelajaran->update($request->all());
            toast('Jam pelajaran berhasil diperbarui.', 'success');
            return redirect()->route('kurikulum.jam-pelajaran.index');
        } catch (\Exception $e) {
            Log::error('Error updating time slot: ' . $e->getMessage());
            toast('Gagal memperbarui jam pelajaran.', 'error');
            return back()->withInput();
        }
    }

    public function destroy(JamPelajaran $jamPelajaran)
    {
        try {
            $jamPelajaran->delete();
            toast('Jam pelajaran berhasil dihapus.', 'success');
        } catch (\Exception $e) {
            Log::error('Error deleting time slot: ' . $e->getMessage());
            toast('Gagal menghapus jam pelajaran. Mungkin masih digunakan.', 'error');
        }
        return redirect()->route('kurikulum.jam-pelajaran.index');
    }
}
