<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Siswa Rombel: {{ $rombel->kelas->nama_kelas }} ({{ $rombel->tahun_ajaran }})
        </h2>
        <p class="text-sm text-gray-500">Wali Kelas: {{ $rombel->waliKelas->name }}</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Tambah Siswa -->
            <div class="md:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Tambah Siswa ke Rombel</h3>
                        <form action="{{ route('master-data.rombel.add-siswa', $rombel->id) }}" method="POST">
                            @csrf
                            <div>
                                <x-input-label for="siswa_ids" :value="__('Pilih Siswa Tersedia')" />
                                <select name="siswa_ids[]" id="siswa_ids" multiple class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-64">
                                    @forelse ($siswaTersedia as $siswa)
                                        <option value="{{ $siswa->id }}">{{ $siswa->nis }} - {{ $siswa->nama_lengkap }}</option>
                                    @empty
                                        <option disabled>-- Semua siswa sudah masuk rombel --</option>
                                    @endforelse
                                </select>
                                <small class="text-xs text-gray-500">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</small>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>{{ __('Tambahkan') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Daftar Siswa di Rombel -->
            <div class="md:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Daftar Siswa di Rombel Ini ({{ $siswaDiRombel->count() }} siswa)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($siswaDiRombel as $siswa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $siswa->nis }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $siswa->nama_lengkap }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <form action="{{ route('master-data.rombel.remove-siswa', ['rombel' => $rombel->id, 'siswa' => $siswa->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin mengeluarkan siswa ini dari rombel?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Keluarkan</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-4 text-center">Belum ada siswa di rombel ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>