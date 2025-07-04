<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\MasterGuru;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalPelajaranController extends Controller
{
    // Menampilkan halaman pilihan rombel
    public function index()
    {
        $rombels = Rombel::with('kelas', 'waliKelas')->where('tahun_ajaran', '2024/2025')->get(); // Ganti dengan tahun ajaran dinamis
        return view('pages.kurikulum.jadwal-pelajaran.index', compact('rombels'));
    }

    // Menampilkan grid jadwal untuk rombel yang dipilih
    public function show(Rombel $rombel)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamSlots = config('jadwal.jam_slots'); // Kita akan buat file config untuk ini
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $guru = MasterGuru::orderBy('nama_lengkap')->get();

        $jadwal = JadwalPelajaran::where('rombel_id', $rombel->id)
            ->get()
            ->keyBy(function ($item) {
                return $item->hari . '-' . $item->jam_ke;
            });

        return view('pages.kurikulum.jadwal-pelajaran.show', compact('rombel', 'days', 'jamSlots', 'mataPelajaran', 'guru', 'jadwal'));
    }

    // Menyimpan data jadwal dari grid
    public function store(Request $request, Rombel $rombel)
    {
        $jadwalData = $request->input('jadwal', []);
        $jamSlots = config('jadwal.jam_slots');

        DB::beginTransaction();
        try {
            // Hapus jadwal lama untuk rombel ini
            JadwalPelajaran::where('rombel_id', $rombel->id)->delete();

            // Buat jadwal baru dari input
            foreach ($jadwalData as $hari => $jamKeList) {
                foreach ($jamKeList as $jamKe => $data) {
                    if (!empty($data['mata_pelajaran_id']) && !empty($data['master_guru_id'])) {
                        $slot = $jamSlots[$jamKe];
                        JadwalPelajaran::create([
                            'rombel_id' => $rombel->id,
                            'hari' => $hari,
                            'jam_ke' => $jamKe,
                            'jam_mulai' => $slot['mulai'],
                            'jam_selesai' => $slot['selesai'],
                            'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                            'master_guru_id' => $data['master_guru_id'],
                        ]);
                    }
                }
            }

            DB::commit();
            toast('Jadwal pelajaran berhasil disimpan.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Gagal menyimpan jadwal. ' . $e->getMessage(), 'error');
        }

        return redirect()->route('kurikulum.jadwal-pelajaran.show', $rombel->id);
    }
}
