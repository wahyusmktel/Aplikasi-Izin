<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MasterGuru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role; // <-- Tambahkan ini

class MasterGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterGuru::with('user');
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                ->orWhere('nuptk', 'like', '%' . $request->search . '%');
        }
        $guru = $query->latest()->paginate(10);
        return view('pages.kurikulum.master-guru.index', compact('guru'));
    }

    public function create()
    {
        return view('pages.kurikulum.master-guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nuptk' => 'nullable|string|unique:master_gurus,nuptk',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {
            MasterGuru::create($request->all());
            toast('Data guru berhasil ditambahkan.', 'success');
            return redirect()->route('kurikulum.master-guru.index');
        } catch (\Exception $e) {
            Log::error('Error storing teacher: ' . $e->getMessage());
            toast('Gagal menambahkan data guru.', 'error');
            return back()->withInput();
        }
    }

    public function edit(MasterGuru $masterGuru)
    {
        return view('pages.kurikulum.master-guru.edit', compact('masterGuru'));
    }

    public function update(Request $request, MasterGuru $masterGuru)
    {
        $request->validate([
            'nuptk' => 'nullable|string|unique:master_gurus,nuptk,' . $masterGuru->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {
            $masterGuru->update($request->all());
            toast('Data guru berhasil diperbarui.', 'success');
            return redirect()->route('kurikulum.master-guru.index');
        } catch (\Exception $e) {
            Log::error('Error updating teacher: ' . $e->getMessage());
            toast('Gagal memperbarui data guru.', 'error');
            return back()->withInput();
        }
    }

    public function destroy(MasterGuru $masterGuru)
    {
        try {
            if ($masterGuru->user) {
                $masterGuru->user->delete();
            }
            $masterGuru->delete();
            toast('Data guru berhasil dihapus.', 'success');
            return redirect()->route('kurikulum.master-guru.index');
        } catch (\Exception $e) {
            Log::error('Error deleting teacher: ' . $e->getMessage());
            toast('Gagal menghapus data guru.', 'error');
            return back();
        }
    }

    public function generateAkun(MasterGuru $masterGuru)
    {
        if ($masterGuru->user_id) {
            toast('Akun untuk guru ini sudah ada.', 'error');
            return back();
        }

        DB::beginTransaction();
        try {
            $email = str_replace(' ', '.', strtolower($masterGuru->nama_lengkap)) . '@smktelkom-lpg.sch.id';
            $password = 'rahasia123'; // Password default

            // Cek duplikasi email
            if (User::where('email', $email)->exists()) {
                $email = str_replace(' ', '.', strtolower($masterGuru->nama_lengkap)) . rand(10, 99) . '@smktelkom-lpg.sch.id';
            }

            $user = User::create([
                'name' => $masterGuru->nama_lengkap,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            // Assign role 'Guru Kelas' ke user baru
            $user->assignRole('Guru Kelas');

            $masterGuru->update(['user_id' => $user->id]);

            DB::commit();
            toast('Akun berhasil dibuat! Email: ' . $email, 'success')->autoClose(8000);
            return redirect()->route('kurikulum.master-guru.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating teacher account: ' . $e->getMessage());
            toast('Terjadi kesalahan saat membuat akun.', 'error');
            return back();
        }
    }
}
