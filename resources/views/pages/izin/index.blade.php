<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Perizinan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
                        <form action="{{ route('izin.index') }}" method="GET" class="w-full sm:w-1/3">
                            <div class="flex">
                                <x-text-input id="search" name="search" type="text" class="w-full" placeholder="Cari berdasarkan keterangan..." :value="request('search')" />
                                <x-primary-button class="ms-2">
                                    {{ __('Cari') }}
                                </x-primary-button>
                            </div>
                        </form>

                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'ajukan-izin-modal')">
                            {{ __('Ajukan Izin Tidak Masuk') }}
                        </x-primary-button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Izin</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($perizinan as $izin)
                                    <tr x-data="{ item: {{ json_encode($izin) }} }">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->isoFormat('D MMMM YYYY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap truncate max-w-sm">{{ strip_tags($izin->keterangan) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = [
                                                    'diajukan' => 'bg-yellow-100 text-yellow-800',
                                                    'disetujui' => 'bg-green-100 text-green-800',
                                                    'ditolak' => 'bg-red-100 text-red-800',
                                                ][$izin->status];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                <div x-data="{ tooltip: false }" class="relative">
                                                    <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" @click="$dispatch('open-modal', { name: 'lihat-izin-modal', item: item })" class="text-blue-600 hover:text-blue-900">
                                                        <span x-show="tooltip" class="absolute z-10 w-auto p-2 -mt-10 text-xs leading-tight text-white transform -translate-x-1/2 bg-gray-800 rounded-lg left-1/2">Lihat</span>
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div>

                                                @if ($izin->status == 'diajukan')
                                                    <div x-data="{ tooltip: false }" class="relative">
                                                        <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" @click="$dispatch('open-modal', 'edit-izin-modal-{{ $izin->id }}')" class="text-indigo-600 hover:text-indigo-900">
                                                            <span x-show="tooltip" class="absolute z-10 w-auto p-2 -mt-10 text-xs leading-tight text-white transform -translate-x-1/2 bg-gray-800 rounded-lg left-1/2">Edit</span>
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </div>

                                                    <div x-data="{ tooltip: false }" class="relative">
                                                        <form action="{{ route('izin.destroy', $izin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="text-red-600 hover:text-red-900">
                                                                <span x-show="tooltip" class="absolute z-10 w-auto p-2 -mt-10 text-xs leading-tight text-white transform -translate-x-1/2 bg-gray-800 rounded-lg left-1/2">Batalkan</span>
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Anda belum memiliki riwayat perizinan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $perizinan->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>

        @include('pages.izin.partials.modal-form-izin')
        @include('pages.izin.partials.modal-lihat-izin')
        @foreach ($perizinan as $izin)
            @if ($izin->status == 'diajukan')
                @include('pages.izin.partials.modal-edit-izin', ['izin' => $izin])
            @endif
        @endforeach
    </x-app-layout>