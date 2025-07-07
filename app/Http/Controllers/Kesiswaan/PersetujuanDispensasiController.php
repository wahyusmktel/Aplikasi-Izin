<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Models\Dispensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PersetujuanDispensasiController extends Controller
{
    // Menampilkan daftar pengajuan dispensasi
    public function index(Request $request)
    {
        $query = Dispensasi::with(['diajukanOleh'])->withCount('siswa');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $daftarDispensasi = $query->latest()->paginate(15);
        return view('pages.kesiswaan.persetujuan-dispensasi.index', compact('daftarDispensasi'));
    }

    // Menampilkan detail pengajuan untuk ditinjau
    public function show(Dispensasi $dispensasi)
    {
        $dispensasi->load(['diajukanOleh', 'siswa.rombels.kelas']);
        return view('pages.kesiswaan.persetujuan-dispensasi.show', compact('dispensasi'));
    }

    // Menyetujui pengajuan
    public function approve(Dispensasi $dispensasi)
    {
        try {
            $dispensasi->update([
                'status' => 'disetujui',
                'disetujui_oleh_id' => Auth::id(),
            ]);
            // Di sini nanti kita bisa picu notifikasi ke pihak terkait
            toast('Dispensasi berhasil disetujui.', 'success');
        } catch (\Exception $e) {
            Log::error('Error approving dispensation: ' . $e->getMessage());
            toast('Gagal menyetujui dispensasi.', 'error');
        }
        return redirect()->route('kesiswaan.persetujuan-dispensasi.index');
    }

    // Menolak pengajuan
    public function reject(Request $request, Dispensasi $dispensasi)
    {
        $request->validate(['alasan_penolakan' => 'required|string|min:10']);
        try {
            $dispensasi->update([
                'status' => 'ditolak',
                'disetujui_oleh_id' => Auth::id(),
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);
            toast('Dispensasi telah ditolak.', 'info');
        } catch (\Exception $e) {
            Log::error('Error rejecting dispensation: ' . $e->getMessage());
            toast('Gagal menolak dispensasi.', 'error');
        }
        return redirect()->route('kesiswaan.persetujuan-dispensasi.index');
    }

    // Mencetak PDF
    public function printPdf(Dispensasi $dispensasi)
    {
        if ($dispensasi->status !== 'disetujui') {
            abort(403, 'Surat dispensasi ini belum disetujui.');
        }
        $dispensasi->load(['siswa.rombels.kelas', 'diajukanOleh', 'disetujuiOleh']);
        // Kita akan buat route verifikasi ini di langkah berikutnya
        $verificationUrl = route('verifikasi.dispensasi', $dispensasi->id);
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(80)->generate($verificationUrl));

        $pdf = Pdf::loadView('pdf.surat-dispensasi', compact('dispensasi', 'qrCode'));
        return $pdf->stream('surat-dispensasi-' . $dispensasi->nama_kegiatan . '.pdf');
    }
}
