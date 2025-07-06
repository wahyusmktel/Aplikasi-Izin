<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Keterlambatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VerifikasiTerlambatController extends Controller
{
    // Menampilkan daftar siswa yang perlu diverifikasi
    public function index()
    {
        $daftarSiswaTerlambat = Keterlambatan::with('siswa.rombels.kelas')
            ->where('status', 'dicatat_security')
            ->latest('waktu_dicatat_security')
            ->get();

        return view('pages.piket.verifikasi-terlambat.index', compact('daftarSiswaTerlambat'));
    }

    // Menampilkan halaman detail untuk verifikasi
    public function show(Keterlambatan $keterlambatan)
    {
        $keterlambatan->load('siswa.rombels.kelas', 'security');
        return view('pages.piket.verifikasi-terlambat.show', compact('keterlambatan'));
    }

    // Menyimpan data verifikasi dan men-generate PDF
    public function update(Request $request, Keterlambatan $keterlambatan)
    {
        $request->validate(['tindak_lanjut_piket' => 'nullable|string']);

        try {
            // 1. Cari jadwal pelajaran yang sedang berlangsung
            $rombelSiswa = $keterlambatan->siswa->rombels()->first();
            $jadwalSaatIni = null;
            if ($rombelSiswa) {
                $namaHariIni = now()->isoFormat('dddd');
                $waktuSaatIni = now()->format('H:i:s');
                $jadwalSaatIni = JadwalPelajaran::where('rombel_id', $rombelSiswa->id)
                    ->where('hari', $namaHariIni)
                    ->where('jam_mulai', '<=', $waktuSaatIni)
                    ->where('jam_selesai', '>=', $waktuSaatIni)
                    ->first();
            }

            // 2. Update data keterlambatan
            $keterlambatan->update([
                'tindak_lanjut_piket' => $request->tindak_lanjut_piket,
                'diverifikasi_oleh_piket_id' => Auth::id(),
                'waktu_verifikasi_piket' => now(),
                'jadwal_pelajaran_id' => $jadwalSaatIni?->id,
                'status' => 'diverifikasi_piket',
            ]);

            // 3. Siapkan data untuk PDF
            $keterlambatan->load(['siswa.rombels.kelas', 'guruPiket', 'jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.guru']);

            // QR Code untuk Verifikasi Publik
            $publicUrl = route('verifikasi.surat-terlambat', $keterlambatan->uuid);
            $publicQrCode = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(60)->generate($publicUrl));

            // QR Code untuk Verifikasi Guru Kelas
            $guruKelasUrl = route('guru-kelas.verifikasi-terlambat.scan', $keterlambatan->uuid); // Route ini akan kita buat nanti
            $guruKelasQrCode = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(60)->generate($guruKelasUrl));

            // 4. Generate & stream PDF
            $pdf = Pdf::loadView('pdf.surat-izin-masuk-kelas', compact('keterlambatan', 'publicQrCode', 'guruKelasQrCode'));

            return $pdf->stream('surat-izin-masuk-' . $keterlambatan->siswa->nama_lengkap . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error verifying late record: ' . $e->getMessage());
            toast('Gagal memverifikasi data.', 'error');
            return back();
        }
    }
}
