<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Izin Keluar (Piket)') }}
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tujuan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($daftarIzin as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->siswa->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if (in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security', 'selesai'])) bg-green-100 text-green-800
                                                @elseif($izin->status == 'ditolak') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ str_replace('_', ' ', Str::title($izin->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($izin->status == 'disetujui_guru_kelas')
                                                <div class="flex items-center justify-end space-x-2">
                                                    <button x-data
                                                        @click.prevent="$dispatch('open-modal', 'tolak-izin-piket-{{ $izin->id }}')"
                                                        class="text-red-600 hover:text-red-900">Tolak</button>
                                                    <form
                                                        action="{{ route('piket.persetujuan-izin-keluar.approve', $izin->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900">Setujui</button>
                                                    </form>
                                                </div>
                                            @elseif (in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security', 'selesai']))
                                                <a href="{{ route('piket.persetujuan-izin-keluar.print', $izin->id) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                                    <i class="fa-solid fa-print mr-2"></i> Cetak
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($izin->status == 'disetujui_guru_kelas')
                                        @include(
                                            'pages.piket.persetujuan-izin-keluar.partials.modal-tolak',
                                            ['izin' => $izin]
                                        )
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Tidak ada pengajuan izin untuk
                                            diproses.</td>
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
