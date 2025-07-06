<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Keterlambatan: ') . $keterlambatan->siswa->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('piket.verifikasi-terlambat.update', $keterlambatan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 text-gray-900 space-y-4">
                        <div>
                            <h3 class="font-semibold">Detail Siswa</h3>
                            <p><strong>Kelas:</strong>
                                {{ $keterlambatan->siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</p>
                            <p><strong>Dicatat oleh Security:</strong> {{ $keterlambatan->security->name }} pada jam
                                {{ \Carbon\Carbon::parse($keterlambatan->waktu_dicatat_security)->format('H:i') }}</p>
                        </div>
                        <div class="border-t pt-4">
                            <p><strong>Alasan Siswa:</strong></p>
                            <p class="text-gray-700 italic">"{{ $keterlambatan->alasan_siswa }}"</p>
                        </div>
                        <div class="border-t pt-4">
                            <x-input-label for="tindak_lanjut_piket" value="Tindak Lanjut dari Guru Piket (Opsional)" />
                            <textarea name="tindak_lanjut_piket" id="tindak_lanjut_piket" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm">{{ old('tindak_lanjut_piket') }}</textarea>
                            <p class="text-xs text-gray-500">Contoh: Diberikan teguran lisan, diminta membuat surat
                                pernyataan, dll.</p>
                        </div>
                        <div class="flex justify-end pt-4 border-t">
                            <a href="{{ route('piket.verifikasi-terlambat.index') }}"><x-secondary-button
                                    type="button">Batal</x-secondary-button></a>
                            <x-primary-button class="ms-3">Verifikasi & Cetak Surat</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
