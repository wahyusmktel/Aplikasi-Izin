<?php

namespace Database\Seeders;

use App\Models\JamPelajaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data jam pelajaran yang akan diisi
        $jamSlots = [
            1 => ['mulai' => '07:00', 'selesai' => '07:45', 'keterangan' => null],
            2 => ['mulai' => '07:45', 'selesai' => '08:30', 'keterangan' => null],
            3 => ['mulai' => '08:30', 'selesai' => '09:15', 'keterangan' => null],
            4 => ['mulai' => '09:15', 'selesai' => '10:00', 'keterangan' => null],
            // Istirahat
            5 => ['mulai' => '10:15', 'selesai' => '11:00', 'keterangan' => 'Istirahat'],
            6 => ['mulai' => '11:00', 'selesai' => '11:45', 'keterangan' => null],
            7 => ['mulai' => '11:45', 'selesai' => '12:30', 'keterangan' => null],
            // Istirahat & Sholat
            8 => ['mulai' => '13:00', 'selesai' => '13:45', 'keterangan' => 'Istirahat & Sholat'],
            9 => ['mulai' => '13:45', 'selesai' => '14:30', 'keterangan' => null],
            10 => ['mulai' => '14:30', 'selesai' => '15:15', 'keterangan' => null],
        ];

        foreach ($jamSlots as $jamKe => $data) {
            // Gunakan updateOrCreate untuk mencegah duplikasi jika seeder dijalankan lagi
            JamPelajaran::updateOrCreate(
                ['jam_ke' => $jamKe], // Kondisi untuk mencari data
                [
                    'jam_mulai' => $data['mulai'],
                    'jam_selesai' => $data['selesai'],
                    'keterangan' => $data['keterangan'],
                ]
            );
        }

        $this->command->info('Seeder Jam Pelajaran berhasil dijalankan!');
    }
}
