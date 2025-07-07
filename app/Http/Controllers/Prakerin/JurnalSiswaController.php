<?php

namespace App\Http\Controllers\Prakerin;

use App\Http\Controllers\Controller;
use App\Models\PrakerinJurnal;
use App\Models\PrakerinPenempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penempatan = PrakerinPenempatan::where('master_siswa_id', $user->masterSiswa?->id)
            ->where('status', 'aktif')
            ->first();

        $jurnals = collect();
        if ($penempatan) {
            $jurnals = PrakerinJurnal::where('prakerin_penempatan_id', $penempatan->id)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        }

        return view('pages.prakerin.jurnal-siswa.index', compact('penempatan', 'jurnals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prakerin_penempatan_id' => 'required|exists:prakerin_penempatans,id',
            'tanggal' => 'required|date',
            'kegiatan_dilakukan' => 'required|string',
            'kompetensi_yang_didapat' => 'required|string',
            'foto_kegiatan' => 'nullable|image|max:2048', // max 2MB
        ]);

        $path = null;
        if ($request->hasFile('foto_kegiatan')) {
            $path = $request->file('foto_kegiatan')->store('public/jurnal_prakerin');
        }

        PrakerinJurnal::create([
            'prakerin_penempatan_id' => $request->prakerin_penempatan_id,
            'tanggal' => $request->tanggal,
            'kegiatan_dilakukan' => $request->kegiatan_dilakukan,
            'kompetensi_yang_didapat' => $request->kompetensi_yang_didapat,
            'foto_kegiatan' => $path,
        ]);

        toast('Jurnal harian berhasil disimpan.', 'success');
        return redirect()->route('siswa.jurnal-prakerin.index');
    }
}
