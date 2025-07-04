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
    // Method index() tidak berubah
    public function index()
    {
        $rombels = Rombel::with('kelas', 'waliKelas')->where('tahun_ajaran', '2024/2025')->get();
        return view('pages.kurikulum.jadwal-pelajaran.index', compact('rombels'));
    }

    // Method show() dirombak total
    public function show(Rombel $rombel)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamSlots = config('jadwal.jam_slots');
        $guru = MasterGuru::orderBy('nama_lengkap')->get();

        // Ambil jadwal yang sudah ada untuk rombel ini
        $jadwal = JadwalPelajaran::where('rombel_id', $rombel->id)
            ->with(['mataPelajaran', 'guru']) // Eager load the relationships
            ->get();

        // Hitung jam yang sudah dialokasikan untuk setiap mapel
        $jamTerpakai = $jadwal->groupBy('mata_pelajaran_id')->map->count();

        // Siapkan data mata pelajaran dengan sisa jam
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get()->map(function ($mapel) use ($jamTerpakai) {
            $terpakai = $jamTerpakai->get($mapel->id, 0);
            $mapel->sisa_jam = $mapel->jumlah_jam - $terpakai;
            return $mapel;
        });

        // Ubah format jadwal agar mudah diakses oleh Alpine.js
        $jadwalFormatted = $jadwal->keyBy(function ($item) {
            return $item->hari . '-' . $item->jam_ke;
        });

        return view('pages.kurikulum.jadwal-pelajaran.show', compact('rombel', 'days', 'jamSlots', 'mataPelajaran', 'guru', 'jadwalFormatted'));
    }

    // Method store() dirombak untuk menerima format checkbox
    public function store(Request $request, Rombel $rombel)
    {
        $jadwalData = $request->input('jadwal', []);
        $jamSlots = config('jadwal.jam_slots');

        DB::beginTransaction();
        try {
            // Hapus jadwal lama untuk rombel ini
            JadwalPelajaran::where('rombel_id', $rombel->id)->delete();

            // Buat jadwal baru dari input checkbox
            foreach ($jadwalData as $hari => $jamKeList) {
                foreach ($jamKeList as $jamKe => $data) {
                    // Hanya proses jika checkbox ter-checklist (ada datanya)
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
            toast('Gagal menyimpan jadwal: ' . $e->getMessage(), 'error');
        }

        return redirect()->route('kurikulum.jadwal-pelajaran.show', $rombel->id);
    }
}
