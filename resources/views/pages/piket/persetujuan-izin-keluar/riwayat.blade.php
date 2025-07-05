<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Persetujuan Izin Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form Filter -->
                    <form action="{{ route('piket.persetujuan-izin-keluar.riwayat') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <x-text-input type="text" name="search" placeholder="Cari Nama Siswa..."
                                    class="w-full" value="{{ request('search') }}" />
                            </div>
                            <div>
                                <x-text-input type="date" name="start_date" class="w-full"
                                    value="{{ request('start_date') }}" />
                            </div>
                            <div>
                                <x-text-input type="date" name="end_date" class="w-full"
                                    value="{{ request('end_date') }}" />
                            </div>
                            <div class="md:col-span-4 flex justify-end">
                                <x-primary-button type="submit">Filter</x-primary-button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Riwayat -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                        Disetujui</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        Akhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Disetujui Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($riwayatIzin as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->siswa->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $izin->siswa->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($izin->guru_piket_approved_at)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($izin->status == 'selesai') bg-green-100 text-green-800
                                                @elseif($izin->status == 'terlambat') bg-orange-100 text-orange-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ str_replace('_', ' ', Str::title($izin->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $izin->guruPiketApprover->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">Tidak ada riwayat untuk
                                            ditampilkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $riwayatIzin->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
