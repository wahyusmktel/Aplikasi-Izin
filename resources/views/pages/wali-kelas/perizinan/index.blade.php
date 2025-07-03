<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Izin Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('wali-kelas.perizinan.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="search" :value="__('Cari Nama Siswa')" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Ketik nama siswa..." />
                            </div>
                            <div>
                                <x-input-label for="status" :value="__('Filter Status')" />
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>
                                    {{ __('Terapkan') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Izin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($perizinan as $izin)
                                    <tr x-data="{ item: {{ json_encode($izin) }} }">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->isoFormat('D MMMM YYYY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = ['diajukan' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'ditolak' => 'bg-red-100 text-red-800'][$izin->status];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                <button @click="$dispatch('open-modal', { name: 'lihat-izin-wali-kelas', item: item })" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                
                                                @if ($izin->status == 'diajukan')
                                                    <form action="{{ route('wali-kelas.perizinan.approve', $izin->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui pengajuan ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Setujui">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button @click="$dispatch('open-modal', 'tolak-izin-modal-{{ $izin->id }}')" class="text-red-600 hover:text-red-900" title="Tolak">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada data perizinan untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $perizinan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.wali-kelas.perizinan.partials.modal-lihat-izin')
    @foreach ($perizinan as $izin)
        @if ($izin->status == 'diajukan')
            @include('pages.wali-kelas.perizinan.partials.modal-tolak-izin', ['izin' => $izin])
        @endif
    @endforeach
</x-app-layout>