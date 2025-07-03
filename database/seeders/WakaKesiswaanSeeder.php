<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WakaKesiswaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role "Waka Kesiswaan" sudah ada
        $role = Role::firstOrCreate(['name' => 'Waka Kesiswaan']);

        // Buat user baru untuk Waka Kesiswaan
        $wakaUser = User::create([
            'name' => 'Waka Kesiswaan',
            'email' => 'kesiswaan@smktelkom-lpg.sch.id',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
        ]);
        
        // Assign role ke user tersebut
        $wakaUser->assignRole($role);
    }
}