<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Izin Meninggalkan Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <x-primary-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'ajukan-izin-keluar-kelas')">
                    {{ __('+ Buat Pengajuan Baru') }}
                </x-primary-button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold mb-4">Riwayat Pengajuan Anda</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tujuan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($riwayatIzin as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($izin->created_at)->isoFormat('D MMM Y, HH:mm') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if (in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security', 'selesai'])) bg-green-100 text-green-800
                                                @elseif($izin->status == 'ditolak') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ str_replace('_', ' ', Str::title($izin->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Tampilkan Alasan Penolakan jika ada --}}
                                            @if ($izin->status == 'ditolak')
                                                <span class="italic text-red-600">{{ $izin->alasan_penolakan }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Belum ada riwayat pengajuan.
                                        </td>
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

    @include('pages.siswa.izin-keluar-kelas.partials.form-modal', ['jadwalSaatIni' => $jadwalSaatIni])
</x-app-layout>
