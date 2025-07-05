<?php

namespace App\Http\Controllers\GuruKelas;

use App\Http\Controllers\Controller;
use App\Models\IzinMeninggalkanKelas;
use App\Models\JadwalPelajaran;
use App\Models\MasterGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PersetujuanIzinKeluarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterGuru = MasterGuru::where('user_id', $user->id)->first();
        $pengajuanIzin = collect();
        $jadwalSaatIni = null;

        if ($masterGuru) {
            $namaHariIni = $this->getNamaHari(now()->dayOfWeek);
            $waktuSaatIni = now()->format('H:i:s');

            // Cari jadwal guru yang sedang berlangsung saat ini
            $jadwalSaatIni = JadwalPelajaran::with('rombel.kelas')
                ->where('master_guru_id', $masterGuru->id)
                ->where('hari', $namaHariIni)
                ->where('jam_mulai', '<=', $waktuSaatIni)
                ->where('jam_selesai', '>=', $waktuSaatIni)
                ->first();

            // Jika guru sedang mengajar di sebuah kelas
            if ($jadwalSaatIni) {
                $pengajuanIzin = IzinMeninggalkanKelas::with('siswa')
                    ->where('rombel_id', $jadwalSaatIni->rombel_id)
                    ->where('status', 'diajukan')
                    ->get();
            }
        }

        return view('pages.guru-kelas.persetujuan-izin-keluar.index', compact('pengajuanIzin', 'jadwalSaatIni'));
    }

    public function approve(IzinMeninggalkanKelas $izin)
    {
        try {
            $izin->update([
                'status' => 'disetujui_guru_kelas',
                'guru_kelas_approval_id' => Auth::id(),
                'guru_kelas_approved_at' => now(),
            ]);
            toast('Izin berhasil disetujui.', 'success');
        } catch (\Exception $e) {
            Log::error('Error approving leave permit by class teacher: ' . $e->getMessage());
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
            Log::error('Error rejecting leave permit by class teacher: ' . $e->getMessage());
            toast('Gagal menolak izin.', 'error');
        }
        return back();
    }

    private function getNamaHari($dayOfWeek)
    {
        $hari = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        return $hari[$dayOfWeek] ?? 'Tidak Diketahui';
    }
}
