<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Keterlambatan Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-sm text-gray-600">Berikut adalah daftar siswa yang telah dicatat terlambat oleh
                        Security dan menunggu verifikasi Anda.</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                        Dicatat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                        Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($daftarSiswaTerlambat as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item->waktu_dicatat_security)->format('H:i') }}
                                            WIB</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            {{ $item->siswa->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $item->siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('piket.verifikasi-terlambat.show', $item->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Proses & Verifikasi</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada siswa
                                            yang perlu diverifikasi saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
