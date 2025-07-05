<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF facade
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        if (!in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security', 'selesai'])) {
            toast('Izin belum bisa dicetak.', 'error');
            return back();
        }

        $izin->load(['siswa.masterSiswa.rombels.kelas', 'guruKelasApprover', 'guruPiketApprover', 'securityVerifier', 'jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.guru']);

        // 1. URL untuk verifikasi publik (tetap sama)
        $publicUrl = route('verifikasi.surat', $izin->uuid);
        $publicQrCode = QrCode::format('svg')->size(70)->generate($publicUrl);
        $publicQrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($publicQrCode);

        // 2. URL untuk aksi internal security (diubah ke route cerdas yang baru)
        $securityUrl = route('security.verifikasi.process-scan', $izin->uuid); // <-- BARIS INI DIPERBARUI
        $securityQrCode = QrCode::format('svg')->size(70)->generate($securityUrl);
        $securityQrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($securityQrCode);

        $pdf = Pdf::loadView('pdf.surat-izin-keluar', [
            'izin' => $izin,
            'publicQrCodeBase64' => $publicQrCodeBase64,
            'securityQrCodeBase64' => $securityQrCodeBase64,
        ]);

        return $pdf->stream('surat-izin-' . $izin->siswa->name . '.pdf');
    }

    /**
     * Menampilkan halaman riwayat persetujuan izin keluar oleh piket.
     */
    public function riwayat(Request $request)
    {
        $query = IzinMeninggalkanKelas::with(['siswa.masterSiswa.rombels.kelas', 'guruPiketApprover'])
            ->whereNotNull('guru_piket_approval_id'); // Hanya tampilkan yang pernah diproses piket

        // Filter berdasarkan pencarian nama siswa
        if ($request->filled('search')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('guru_piket_approved_at', '>=', $request->start_date)
                ->whereDate('guru_piket_approved_at', '<=', $request->end_date);
        }

        $riwayatIzin = $query->latest('guru_piket_approved_at')->paginate(20);

        return view('pages.piket.persetujuan-izin-keluar.riwayat', compact('riwayatIzin'));
    }
}
