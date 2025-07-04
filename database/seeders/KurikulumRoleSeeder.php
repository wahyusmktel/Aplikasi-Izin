<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class KurikulumRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role baru 'Kurikulum'
        // firstOrCreate akan membuat role jika belum ada, atau mengambilnya jika sudah ada.
        Role::firstOrCreate(['name' => 'Kurikulum']);
    }
}
