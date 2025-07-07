<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use Illuminate\Http\Request;
use App\Models\Dispensasi;

class PublicVerifikasiController extends Controller
{
    /**
     * Menampilkan halaman verifikasi publik untuk surat keterlambatan.
     */
    public function showSuratTerlambat($uuid)
    {
        $keterlambatan = Keterlambatan::where('uuid', $uuid)
            ->with([
                'siswa.rombels.kelas',
                'security',
                'guruPiket',
                'jadwalPelajaran.mataPelajaran',
                'jadwalPelajaran.guru'
            ])
            ->firstOrFail(); // Akan menampilkan error 404 jika UUID tidak ditemukan

        return view('pages.publik.verifikasi-surat-terlambat', compact('keterlambatan'));
    }

    /**
     * Menampilkan halaman verifikasi publik untuk surat dispensasi.
     */
    public function showDispensasi(Dispensasi $dispensasi)
    {
        // Load semua relasi yang dibutuhkan untuk ditampilkan
        $dispensasi->load([
            'siswa.rombels.kelas',
            'diajukanOleh',
            'disetujuiOleh'
        ]);

        return view('pages.publik.verifikasi-dispensasi', compact('dispensasi'));
    }
}
