<?php

// app/Imports/MataPelajaranImport.php

namespace App\Imports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MataPelajaranImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MataPelajaran([
            'kode_mapel'  => $row['kode_mapel'],
            'nama_mapel'  => $row['nama_mapel'],
            'jumlah_jam'  => $row['jumlah_jam'],
        ]);
    }

    // Menentukan baris judul untuk pemetaan kolom
    public function headingRow(): int
    {
        return 1;
    }

    // Menambahkan validasi untuk setiap baris
    public function rules(): array
    {
        return [
            'kode_mapel' => 'required|string|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string',
            'jumlah_jam' => 'required|integer|min:1',
        ];
    }
}
