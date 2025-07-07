<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Dispensasi') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diajukan Oleh
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($daftarDispensasi as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $item->nama_kegiatan }}</td>
                                    <td class="px-6 py-4">{{ $item->diajukanOleh->name }}</td>
                                    <td class="px-6 py-4">{{ $item->siswa_count }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['diajukan' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'ditolak' => 'bg-red-100 text-red-800'][$item->status] }}">{{ Str::title($item->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('kesiswaan.persetujuan-dispensasi.show', $item->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Tinjau</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Tidak ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $daftarDispensasi->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
