<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Izin Keluar Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($jadwalSaatIni)
                        <div class="p-4 mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700">
                            <p class="font-bold">Anda sedang mengajar di kelas:
                                {{ $jadwalSaatIni->rombel->kelas->nama_kelas }}</p>
                            <p>Menampilkan pengajuan izin dari kelas ini.</p>
                        </div>

                        @forelse ($pengajuanIzin as $izin)
                            <div class="border rounded-lg p-4 mb-3 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-lg">{{ $izin->siswa->name }}</p>
                                    <p class="text-sm text-gray-600">Tujuan: <span
                                            class="font-semibold">{{ $izin->tujuan }}</span></p>
                                    <p class="text-sm text-gray-500">Keterangan: {{ $izin->keterangan ?? '-' }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button x-data
                                        @click.prevent="$dispatch('open-modal', 'tolak-izin-{{ $izin->id }}')"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Tolak</button>
                                    <form action="{{ route('guru-kelas.persetujuan-izin-keluar.approve', $izin->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menyetujui izin ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Setujui</button>
                                    </form>
                                </div>
                            </div>
                            @include('pages.guru-kelas.persetujuan-izin-keluar.partials.modal-tolak', [
                                'izin' => $izin,
                            ])
                        @empty
                            <p class="text-center text-gray-500">Tidak ada siswa yang mengajukan izin saat ini.</p>
                        @endforelse
                    @else
                        <p class="text-center text-gray-500">Anda tidak sedang mengajar di kelas manapun saat ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
