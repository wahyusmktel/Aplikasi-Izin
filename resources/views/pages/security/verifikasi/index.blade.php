<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Izin Keluar Masuk Gerbang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tujuan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                        Keluar</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($daftarIzin as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->siswa->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $izin->siswa->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $izin->waktu_keluar_sebenarnya ? \Carbon\Carbon::parse($izin->waktu_keluar_sebenarnya)->format('H:i') . ' WIB' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($izin->status == 'disetujui_guru_piket')
                                                <form action="{{ route('security.verifikasi.keluar', $izin->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                                        Verifikasi Keluar
                                                    </button>
                                                </form>
                                            @elseif ($izin->status == 'diverifikasi_security')
                                                <form action="{{ route('security.verifikasi.kembali', $izin->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                                        Siswa Kembali
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">Tidak ada siswa yang perlu
                                            diverifikasi saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $daftarIzin->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
