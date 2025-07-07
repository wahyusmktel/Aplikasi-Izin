<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tinjau Pengajuan Dispensasi: {{ $dispensasi->nama_kegiatan }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        <div>
                            <p><strong>Diajukan oleh:</strong> {{ $dispensasi->diajukanOleh->name }}</p>
                        </div>
                        <div>
                            <p><strong>Waktu:</strong>
                                {{ \Carbon\Carbon::parse($dispensasi->waktu_mulai)->isoFormat('D MMM Y, HH:mm') }} -
                                {{ \Carbon\Carbon::parse($dispensasi->waktu_selesai)->isoFormat('D MMM Y, HH:mm') }}</p>
                        </div>
                        <div class="border-t pt-4">
                            <p><strong>Keterangan Kegiatan:</strong><br>{{ $dispensasi->keterangan }}</p>
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="font-semibold">Daftar Siswa Terlibat ({{ $dispensasi->siswa->count() }}):</h4>
                            <ul class="list-decimal list-inside mt-2 text-sm">
                                @foreach ($dispensasi->siswa as $siswa)
                                    <li>{{ $siswa->nama_lengkap }}
                                        ({{ $siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }})</li>
                                @endforeach
                            </ul>
                        </div>
                        @if ($dispensasi->status == 'diajukan')
                            <div class="border-t pt-6 flex justify-end space-x-2">
                                <x-danger-button x-data
                                    @click.prevent="$dispatch('open-modal', 'tolak-dispensasi-modal')">Tolak</x-danger-button>
                                <form action="{{ route('kesiswaan.persetujuan-dispensasi.approve', $dispensasi->id) }}"
                                    method="POST">
                                    @csrf @method('PATCH')
                                    <x-primary-button>Setujui</x-primary-button>
                                </form>
                            </div>
                        @else
                            <div class="border-t pt-4">
                                <p><strong>Status:</strong> Permohonan ini telah <span
                                        class="font-bold">{{ $dispensasi->status }}</span> oleh Anda.</p>
                                @if ($dispensasi->status == 'disetujui')
                                    <a href="{{ route('kesiswaan.persetujuan-dispensasi.print', $dispensasi->id) }}"
                                        target="_blank" class="inline-block mt-2 text-blue-600 hover:underline">Cetak
                                        Surat Dispensasi</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal name="tolak-dispensasi-modal" focusable>
        <form method="post" action="{{ route('kesiswaan.persetujuan-dispensasi.reject', $dispensasi->id) }}"
            class="p-6">
            @csrf @method('patch')
            <h2 class="text-lg font-medium text-gray-900">Tolak Pengajuan Dispensasi</h2>
            <p class="mt-1 text-sm text-gray-600">Berikan alasan penolakan.</p>
            <div class="mt-6">
                <textarea name="alasan_penolakan" class="w-full border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mt-6 flex justify-end"><x-secondary-button
                    x-on:click="$dispatch('close')">Batal</x-secondary-button><x-danger-button
                    class="ms-3">Tolak</x-danger-button></div>
        </form>
    </x-modal>
</x-app-layout>
