<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF facade

class PersetujuanIzinKeluarController extends Controller
{
    public function index()
    {
        // Ambil semua izin yang sudah disetujui guru kelas atau sudah diproses oleh piket
        $daftarIzin = IzinMeninggalkanKelas::with(['siswa', 'guruKelasApprover'])
            ->whereIn('status', ['disetujui_guru_kelas', 'disetujui_guru_piket', 'diverifikasi_security', 'selesai'])
            ->latest()
            ->paginate(15);

        return view('pages.piket.persetujuan-izin-keluar.index', compact('daftarIzin'));
    }

    public function approve(IzinMeninggalkanKelas $izin)
    {
        try {
            $izin->update([
                'status' => 'disetujui_guru_piket',
                'guru_piket_approval_id' => Auth::id(),
                'guru_piket_approved_at' => now(),
            ]);
            toast('Izin berhasil disetujui. Silakan cetak surat izin.', 'success');
        } catch (\Exception $e) {
            Log::error('Error approving leave permit by picket teacher: ' . $e->getMessage());
            toast('Gagal menyetujui izin.', 'error');
        }
        return back();
    }

    public function reject(Request $request, IzinMeninggalkanKelas $izin)
    {
        $request->validate(['alasan_penolakan' => 'required|string|min:5']);
        try {
            $izin->update([
                'status' => 'ditolak',
                'ditolak_oleh' => Auth::id(),
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);
            toast('Izin telah ditolak.', 'info');
        } catch (\Exception $e) {
            Log::error('Error rejecting leave permit by picket teacher: ' . $e->getMessage());
            toast('Gagal menolak izin.', 'error');
        }
        return back();
    }

    public function printPdf(IzinMeninggalkanKelas $izin)
    {
        // Pastikan hanya izin yang sudah disetujui piket atau lebih yang bisa dicetak
        if (!in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security', 'selesai'])) {
            toast('Izin belum bisa dicetak.', 'error');
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

        return $pdf->stream('surat-izin-' . $izin->siswa->name . '.pdf');
    }
}
