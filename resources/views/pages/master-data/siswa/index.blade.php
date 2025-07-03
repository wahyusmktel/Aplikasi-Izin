<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Tombol Aksi Atas --}}
                    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('master-data.siswa.index') }}" method="GET" class="w-full sm:w-1/3">
                            <x-text-input id="search" name="search" type="text" class="w-full" placeholder="Cari NIS atau Nama..." :value="request('search')" />
                        </form>
                        {{-- Grup Tombol Kanan --}}
                        <div class="flex items-center justify-end space-x-2">
                            <form action="{{ route('master-data.siswa.generate-akun-masal') }}" method="POST" onsubmit="return confirm('Proses ini akan membuat akun untuk semua siswa yang belum punya. Lanjutkan?');">
                                @csrf
                                <x-secondary-button type="submit">{{ __('Generate Akun Masal') }}</x-secondary-button>
                            </form>
                            <a href="{{ route('master-data.siswa.create') }}">
                                <x-primary-button>{{ __('Tambah Siswa') }}</x-primary-button>
                            </a>
                        </div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Akun</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($siswa as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($item->user)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Dibuat</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-2">
                                                @if($item->user)
                                                    {{-- Tombol Reset Password --}}
                                                    <form action="{{ route('master-data.siswa.reset-password', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset password siswa ini?');">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 text-xs text-white bg-orange-500 hover:bg-orange-600 rounded">Reset Pass</button>
                                                    </form>
                                                @else
                                                    {{-- Tombol Generate Akun Individual --}}
                                                    <form action="{{ route('master-data.siswa.generate-akun', $item->id) }}" method="POST" onsubmit="return confirm('Buat akun login untuk siswa ini?');">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 text-xs text-white bg-blue-500 hover:bg-blue-600 rounded">Generate Akun</button>
                                                    </form>
                                                @endif
                                                
                                                {{-- Tombol Edit & Hapus --}}
                                                <a href="{{ route('master-data.siswa.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('master-data.siswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $siswa->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>