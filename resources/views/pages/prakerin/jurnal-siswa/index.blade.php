{{-- File: index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Jurnal Harian Prakerin') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($penempatan)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Kolom Kiri: Form Input Jurnal --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-lg mb-4">Tambah Jurnal Baru</h3>
                            <form method="POST" action="{{ route('siswa.jurnal-prakerin.store') }}"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="prakerin_penempatan_id" value="{{ $penempatan->id }}">
                                <div><x-input-label for="tanggal" value="Tanggal Kegiatan" /><x-text-input
                                        id="tanggal" type="date" name="tanggal" class="mt-1 block w-full"
                                        :value="old('tanggal', date('Y-m-d'))" required /></div>
                                <div><x-input-label for="kegiatan_dilakukan" value="Kegiatan yang Dilakukan" />
                                    <textarea name="kegiatan_dilakukan" id="kegiatan_dilakukan" rows="4"
                                        class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('kegiatan_dilakukan') }}</textarea>
                                </div>
                                <div><x-input-label for="kompetensi_yang_didapat" value="Kompetensi yang Didapat" />
                                    <textarea name="kompetensi_yang_didapat" id="kompetensi_yang_didapat" rows="3"
                                        class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('kompetensi_yang_didapat') }}</textarea>
                                </div>
                                <div><x-input-label for="foto_kegiatan" value="Foto Kegiatan (Opsional)" /><input
                                        id="foto_kegiatan" type="file" name="foto_kegiatan"
                                        class="mt-1 block w-full text-sm"></div>
                                <div class="flex justify-end"><x-primary-button>Simpan Jurnal</x-primary-button></div>
                            </form>
                        </div>
                    </div>
                    {{-- Kolom Kanan: Riwayat Jurnal --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-lg mb-4">Riwayat Jurnal Anda</h3>
                            <div class="space-y-4">
                                @forelse($jurnals as $jurnal)
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between items-center">
                                            <p class="font-bold">
                                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                            </p>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full {{ ['menunggu' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'revisi' => 'bg-red-100 text-red-800'][$jurnal->status_verifikasi] }}">{{ Str::title($jurnal->status_verifikasi) }}</span>
                                        </div>
                                        <p class="text-sm mt-2">
                                            <strong>Kegiatan:</strong><br>{{ $jurnal->kegiatan_dilakukan }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Belum ada jurnal yang diisi.</p>
                                @endforelse
                            </div>
                            <div class="mt-4">{{ $jurnals->links() }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white text-center p-12 rounded-lg shadow">
                    <h3 class="text-xl font-bold text-gray-700">Anda Tidak Terdaftar</h3>
                    <p class="text-gray-500 mt-2">Anda tidak sedang terdaftar dalam program Prakerin aktif saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
