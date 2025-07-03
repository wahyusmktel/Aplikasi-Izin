<?php

// lang/id/validation.php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string'   => 'Kolom :attribute harus berupa teks.',
    'email'    => 'Kolom :attribute harus berupa alamat email yang valid.',
    'unique'   => ':attribute sudah digunakan.',
    'exists'   => ':attribute yang dipilih tidak valid.',
    'date'     => ':attribute bukan tanggal yang valid.',
    'file'     => ':attribute harus berupa sebuah berkas.',
    'mimes'    => ':attribute harus berupa berkas berjenis: :values.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',

    'min' => [
        'string'  => 'Kolom :attribute minimal harus :min karakter.',
        'numeric' => 'Kolom :attribute minimal harus bernilai :min.',
        'file'    => 'Ukuran file :attribute minimal harus :min kilobyte.',
    ],

    'max' => [
        'string'  => 'Kolom :attribute maksimal harus :max karakter.',
        'numeric' => 'Kolom :attribute maksimal harus bernilai :max.',
        'file'    => 'Ukuran file :attribute maksimal harus :max kilobyte.',
    ],

    'attributes' => [
        'name'                  => 'Nama',
        'email'                 => 'Email',
        'password'              => 'Password',
        'role'                  => 'Peran',
        'tanggal_izin'          => 'Tanggal Izin',
        'keterangan'            => 'Keterangan',
        'dokumen_pendukung'     => 'Dokumen Pendukung',
    ],
];