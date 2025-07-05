<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifikasiController extends Controller
{
    public function index()
    {
        // Ambil izin yang perlu diverifikasi (baru disetujui piket)
        // dan yang sedang berada di luar (sudah diverifikasi keluar)
        $daftarIzin = IzinMeninggalkanKelas::with(['siswa.masterSiswa.rombels.kelas'])
            ->whereIn('status', ['disetujui_guru_piket', 'diverifikasi_security'])
            ->latest()
            ->paginate(20);

        return view('pages.security.verifikasi.index', compact('daftarIzin'));
    }

    /**
     * Memverifikasi siswa saat akan keluar gerbang.
     */
    public function verifyKeluar(IzinMeninggalkanKelas $izin)
    {
        try {
            $izin->update([
                'status' => 'diverifikasi_security',
                'security_verification_id' => Auth::id(),
                'security_verified_at' => now(),
                'waktu_keluar_sebenarnya' => now(),
            ]);
            toast('Siswa telah diverifikasi keluar.', 'success');
        } catch (\Exception $e) {
            Log::error('Error verifying leave by security: ' . $e->getMessage());
            toast('Gagal melakukan verifikasi.', 'error');
        }
        return back();
    }

    /**
     * Memverifikasi siswa saat kembali ke sekolah.
     */
    public function verifyKembali(IzinMeninggalkanKelas $izin)
    {
        try {
            $waktuKembali = now();
            $estimasiKembali = $izin->estimasi_kembali;

            // Tentukan status akhir: selesai atau terlambat
            $statusAkhir = $waktuKembali->gt($estimasiKembali) ? 'terlambat' : 'selesai';

            $izin->update([
                'status' => $statusAkhir,
                'waktu_kembali_sebenarnya' => $waktuKembali,
            ]);
            toast('Siswa telah diverifikasi kembali ke sekolah.', 'success');
        } catch (\Exception $e) {
            Log::error('Error verifying return by security: ' . $e->getMessage());
            toast('Gagal melakukan verifikasi.', 'error');
        }
        return back();
    }

    /**
     * Mencetak surat izin yang sudah diverifikasi.
     */
    public function printPdf(IzinMeninggalkanKelas $izin)
    {
        // Pastikan hanya izin yang sudah diverifikasi atau lebih yang bisa dicetak ulang
        if (!in_array($izin->status, ['diverifikasi_security', 'selesai', 'terlambat'])) {
            toast('Surat izin ini belum bisa dicetak dari sisi security.', 'error');
            return back();
        }

        // Load relasi yang dibutuhkan untuk PDF
        $izin->load(['siswa.masterSiswa.rombels.kelas', 'guruKelasApprover', 'guruPiketApprover', 'securityVerifier']);

        // Buat URL verifikasi untuk QR Code
        $verificationUrl = route('verifikasi.surat', $izin->uuid);

        $pdf = Pdf::loadView('pdf.surat-izin-keluar', [
            'izin' => $izin,
            'verificationUrl' => $verificationUrl,
        ]);

        return $pdf->stream('surat-izin-terverifikasi-' . $izin->siswa->name . '.pdf');
    }
}
