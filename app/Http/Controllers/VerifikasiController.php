<?php

namespace App\Http\Controllers;

use App\Models\IzinMeninggalkanKelas;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Menampilkan halaman verifikasi keabsahan surat izin.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function show(string $uuid)
    {
        $izin = IzinMeninggalkanKelas::with([
            'siswa.masterSiswa.rombels.kelas',
            'guruKelasApprover',
            'guruPiketApprover',
            'securityVerifier'
        ])->where('uuid', $uuid)->firstOrFail();

        return view('pages.verifikasi.show', compact('izin'));
    }
}
