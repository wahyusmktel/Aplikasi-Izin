<?php

namespace App\Http\Controllers\Prakerin;

use App\Http\Controllers\Controller;
use App\Models\MasterGuru;
use App\Models\MasterSiswa;
use App\Models\PrakerinIndustri;
use App\Models\PrakerinPenempatan;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PrakerinPenempatan::with(['siswa', 'industri', 'guruPembimbing']);
        // Tambahkan filter jika perlu
        $penempatan = $query->latest()->paginate(15);
        return view('pages.prakerin.penempatan.index', compact('penempatan'));
    }

    public function create()
    {
        // Ambil siswa yang belum ditempatkan
        $siswa = MasterSiswa::whereDoesntHave('penempatan')->orderBy('nama_lengkap')->get();
        $industri = PrakerinIndustri::orderBy('nama_industri')->get();
        $guru = MasterGuru::orderBy('nama_lengkap')->get();
        return view('pages.prakerin.penempatan.create', compact('siswa', 'industri', 'guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_siswa_id' => 'required|exists:master_siswa,id|unique:prakerin_penempatans,master_siswa_id',
            'prakerin_industri_id' => 'required|exists:prakerin_industris,id',
            'master_guru_id' => 'required|exists:master_gurus,id',
            'nama_pembimbing_industri' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
        PrakerinPenempatan::create($request->all());
        toast('Penempatan siswa berhasil disimpan.', 'success');
        return redirect()->route('prakerin.penempatan.index');
    }

    // Tambahkan relasi ke Model MasterSiswa
    // Buka file app/Models/MasterSiswa.php
    // public function penempatan() { return $this->hasOne(PrakerinPenempatan::class, 'master_siswa_id'); }
}
